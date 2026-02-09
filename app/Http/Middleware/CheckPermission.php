<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckPermission Middleware
 *
 * Placeholder para verificação de permissões.
 * Inicialmente retorna true — implementação real virá com Policies na ETAPA 3.
 *
 * TODO ETAPA 3: Implementar verificação real de permissões via tabela person_permissions.
 */
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $permission
     * @return Response
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        // TODO ETAPA 3: Implementar verificação real
        // if ($permission && !$request->person->hasPermission($permission)) {
        //     return response()->json(['message' => 'Sem permissão.'], 403);
        // }

        // Por enquanto, permite tudo (placeholder)
        return $next($request);
    }
}
