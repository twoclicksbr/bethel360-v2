<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ResolvePersonFromUser Middleware
 *
 * Após autenticação, resolve User->person e injeta no request como $request->person.
 * Facilita acesso à Person autenticada em toda a aplicação.
 */
class ResolvePersonFromUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            // Carregar person relacionado ao user
            $person = $request->user()->person;

            if (!$person) {
                return response()->json([
                    'message' => 'Usuário sem pessoa associada.',
                ], 403);
            }

            // Injetar person no request
            $request->merge(['person' => $person]);

            // Também disponibilizar via atributo customizado
            $request->attributes->set('person', $person);
        }

        return $next($request);
    }
}
