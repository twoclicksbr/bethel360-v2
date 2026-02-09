<?php

namespace App\Http\Controllers\V2\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ForgotPasswordController
 *
 * Placeholder para recuperação de senha.
 * TODO: Implementar envio de email com token de reset.
 */
class ForgotPasswordController extends Controller
{
    /**
     * Solicitar reset de senha.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users_tenant,email',
        ]);

        // TODO: Implementar lógica de envio de email com token
        // 1. Gerar token único e salvar no banco
        // 2. Enviar email com link de reset
        // 3. Criar rota para validar token e permitir nova senha

        return response()->json([
            'message' => 'Se o email existir em nossa base, você receberá instruções para redefinir sua senha.',
        ]);
    }
}
