<?php

namespace App\Http\Controllers\V2\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * LogoutController
 *
 * Revoga o token atual do usuário autenticado.
 */
class LogoutController extends Controller
{
    /**
     * Logout de usuário.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Revogar token atual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }
}
