<?php

namespace App\Http\Controllers\V2\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * RegisterController
 *
 * Registro de novo usuário.
 * Se email existe em contacts de Person (convidado), vincula User à Person existente.
 * Se não, cria Person + User.
 * ID da pessoa vira código 6 dígitos (id 42 → 000042).
 */
class RegisterController extends Controller
{
    /**
     * Registrar novo usuário.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Aceita tanto 'name' quanto 'first_name'+'last_name'
        $request->validate([
            'first_name' => 'required_without:name|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'name' => 'required_without:first_name|string|max:255',
            'email' => 'required|email|unique:users_tenant,email',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'nullable|date',
            'gender_id' => 'nullable|exists:genders,id',
        ]);

        return DB::transaction(function () use ($request) {
            // Determinar first_name e last_name
            if ($request->has('first_name')) {
                $firstName = $request->first_name;
                $lastName = $request->last_name;
                $fullName = trim($firstName . ' ' . ($lastName ?? ''));
            } else {
                // Split do campo 'name'
                $nameParts = explode(' ', $request->name);
                $firstName = $nameParts[0];
                $lastName = implode(' ', array_slice($nameParts, 1)) ?: null;
                $fullName = $request->name;
            }

            // Criar nova Person
            $person = Person::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'birth_date' => $request->birth_date,
                'gender_id' => $request->gender_id,
            ]);

            // Gerar QR Code (6 dígitos) manualmente (PersonObserver virá na ETAPA 3)
            $person->qr_code = str_pad($person->id, 6, '0', STR_PAD_LEFT);
            $person->save();

            // Criar contato de email
            $emailTypeId = DB::table('type_contacts')->where('slug', 'email')->value('id');

            Contact::create([
                'contactable_type' => Person::class,
                'contactable_id' => $person->id,
                'type_contact_id' => $emailTypeId,
                'value' => $request->email,
                'is_primary' => true,
                'is_verified' => true,
            ]);

            // Criar User vinculado à Person
            $user = User::create([
                'person_id' => $person->id,
                'name' => $fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Gerar token Sanctum
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'person_id' => $user->person_id,
                        'qr_code' => $person->qr_code,
                    ],
                    'token' => $token,
                ],
            ], 201);
        });
    }
}
