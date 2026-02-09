<?php

namespace App\Http\Controllers\V2\Auth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * LoginController
 *
 * Autenticação via Sanctum.
 * Recebe email+senha, valida e retorna token.
 * Se User não existe mas Person existe com email em contacts (convidado), orienta cadastro.
 */
class LoginController extends Controller
{
    /**
     * Login de usuário.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Buscar usuário pelo email
        $user = User::where('email', $request->email)->first();

        // Se User não existe, verificar se existe Person convidada
        if (!$user) {
            $contact = Contact::where('contact', $request->email)
                ->where('type_contact_id', function ($query) {
                    $query->select('id')
                        ->from('type_contacts')
                        ->where('slug', 'email')
                        ->limit(1);
                })
                ->first();

            if ($contact && $contact->contactable_type === Person::class) {
                return response()->json([
                    'message' => 'Você foi convidado para a plataforma mas ainda não completou seu cadastro. Por favor, registre-se primeiro.',
                    'action' => 'register',
                ], 401);
            }

            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Verificar senha
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        // Gerar token Sanctum
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'person_id' => $user->person_id,
                ],
                'token' => $token,
            ],
        ]);
    }
}
