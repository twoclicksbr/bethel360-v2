<?php

namespace App\Http\Middleware;

use App\Models\GroupPerson;
use App\Models\MinistryPerson;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureActiveStatus Middleware
 *
 * Verifica se person não está com todos vínculos inativos.
 * Se todas as participações (ministry_persons e group_persons) estiverem inativas,
 * bloqueia acesso a certas funcionalidades.
 */
class EnsureActiveStatus
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
        $person = $request->person ?? $request->user()?->person;

        if (!$person) {
            return response()->json([
                'message' => 'Pessoa não identificada.',
            ], 403);
        }

        // Verificar se tem ao menos um vínculo ativo
        $hasActiveMinistry = MinistryPerson::where('person_id', $person->id)
            ->whereHas('status', function ($query) {
                $query->where('slug', 'ativo');
            })
            ->exists();

        $hasActiveGroup = GroupPerson::where('person_id', $person->id)
            ->whereHas('status', function ($query) {
                $query->where('slug', 'ativo');
            })
            ->exists();

        if (!$hasActiveMinistry && !$hasActiveGroup) {
            return response()->json([
                'message' => 'Você não possui vínculos ativos. Entre em contato com a liderança.',
            ], 403);
        }

        return $next($request);
    }
}
