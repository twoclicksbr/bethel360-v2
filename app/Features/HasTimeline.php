<?php

namespace App\Features;

use Illuminate\Support\Collection;

/**
 * HasTimeline Trait
 *
 * Constrói timeline cronológica da pessoa.
 * Merge de: MinistryPerson, GroupPerson, Presence, Achievement, FamilyLink, Finance.
 * Timeline começa no primeiro contato (mesmo antes de cadastro completo).
 * Usado por: Person
 */
trait HasTimeline
{
    /**
     * Build complete timeline for the person.
     *
     * @return Collection
     */
    public function timeline(): Collection
    {
        $events = collect();

        // MinistryPerson events
        foreach ($this->ministryPersons as $mp) {
            $events->push([
                'type' => 'ministry_enrollment',
                'date' => $mp->enrolled_at ?? $mp->created_at,
                'description' => "Inscrito em {$mp->ministry->name}",
                'ministry_id' => $mp->ministry_id,
                'ministry_name' => $mp->ministry->name,
                'role' => $mp->role->name ?? null,
                'status' => $mp->status->name ?? null,
                'data' => $mp,
            ]);

            if ($mp->completed_at) {
                $events->push([
                    'type' => 'ministry_completed',
                    'date' => $mp->completed_at,
                    'description' => "Concluído {$mp->ministry->name}",
                    'ministry_id' => $mp->ministry_id,
                    'ministry_name' => $mp->ministry->name,
                    'data' => $mp,
                ]);
            }
        }

        // GroupPerson events
        foreach ($this->groupPersons as $gp) {
            $events->push([
                'type' => 'group_enrollment',
                'date' => $gp->enrolled_at ?? $gp->created_at,
                'description' => "Inscrito em {$gp->group->name}",
                'group_id' => $gp->group_id,
                'group_name' => $gp->group->name,
                'ministry_name' => $gp->group->ministry->name ?? null,
                'role' => $gp->role->name ?? null,
                'status' => $gp->status->name ?? null,
                'data' => $gp,
            ]);

            if ($gp->completed_at) {
                $events->push([
                    'type' => 'group_completed',
                    'date' => $gp->completed_at,
                    'description' => "Concluído {$gp->group->name}",
                    'group_id' => $gp->group_id,
                    'group_name' => $gp->group->name,
                    'data' => $gp,
                ]);
            }
        }

        // Presence events (últimas 100 para não sobrecarregar)
        $recentPresences = $this->presences()
            ->orderBy('registered_at', 'desc')
            ->limit(100)
            ->get();

        foreach ($recentPresences as $presence) {
            $events->push([
                'type' => 'presence',
                'date' => $presence->registered_at,
                'description' => 'Presença registrada',
                'presence_method' => $presence->presenceMethod->name ?? null,
                'event_id' => $presence->event_id ?? null,
                'data' => $presence,
            ]);
        }

        // Achievement events
        foreach ($this->achievements as $achievement) {
            $events->push([
                'type' => 'achievement',
                'date' => $achievement->achieved_at ?? $achievement->created_at,
                'description' => "Conquista: {$achievement->title}",
                'title' => $achievement->title,
                'data' => $achievement,
            ]);
        }

        // FamilyLink events (apenas aceitos)
        $acceptedLinks = $this->familyLinks()
            ->where('status', 'accepted')
            ->get();

        foreach ($acceptedLinks as $link) {
            $events->push([
                'type' => 'family_link',
                'date' => $link->accepted_at ?? $link->created_at,
                'description' => "Vínculo familiar: {$link->relationship->name}",
                'relationship' => $link->relationship->name ?? null,
                'related_person_name' => $link->relatedPerson->full_name ?? null,
                'data' => $link,
            ]);
        }

        // Finance events (últimas 50)
        $recentFinances = $this->finances()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        foreach ($recentFinances as $finance) {
            $events->push([
                'type' => 'finance',
                'date' => $finance->created_at,
                'description' => "Movimentação financeira: {$finance->financeType->name}",
                'finance_type' => $finance->financeType->name ?? null,
                'amount' => $finance->amount,
                'data' => $finance,
            ]);
        }

        // Sort by date descending (most recent first)
        return $events->sortByDesc('date')->values();
    }

    /**
     * Get timeline filtered by type.
     *
     * @param string $type
     * @return Collection
     */
    public function timelineByType(string $type): Collection
    {
        return $this->timeline()->where('type', $type)->values();
    }

    /**
     * Get timeline for date range.
     *
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return Collection
     */
    public function timelineByDateRange($startDate, $endDate): Collection
    {
        return $this->timeline()->filter(function ($event) use ($startDate, $endDate) {
            return $event['date'] >= $startDate && $event['date'] <= $endDate;
        })->values();
    }
}
