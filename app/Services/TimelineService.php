<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Collection;

/**
 * TimelineService
 *
 * Constrói timeline cronológica da pessoa.
 * Merge de: MinistryPerson, GroupPerson, Presence, Achievement, FamilyLink, Finance.
 * Timeline começa no primeiro contato (mesmo antes de cadastro completo).
 */
class TimelineService
{
    /**
     * Build complete timeline for person.
     *
     * @param Person $person
     * @return Collection
     */
    public function build(Person $person): Collection
    {
        return $person->timeline();
    }

    /**
     * Get timeline filtered by type.
     *
     * @param Person $person
     * @param string $type
     * @return Collection
     */
    public function buildByType(Person $person, string $type): Collection
    {
        return $person->timelineByType($type);
    }

    /**
     * Get timeline for date range.
     *
     * @param Person $person
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return Collection
     */
    public function buildByDateRange(Person $person, $startDate, $endDate): Collection
    {
        return $person->timelineByDateRange($startDate, $endDate);
    }

    /**
     * Get summary statistics from timeline.
     *
     * @param Person $person
     * @return array
     */
    public function getStatistics(Person $person): array
    {
        $timeline = $person->timeline();

        return [
            'total_events' => $timeline->count(),
            'ministries_count' => $timeline->where('type', 'ministry_enrollment')->count(),
            'groups_count' => $timeline->where('type', 'group_enrollment')->count(),
            'presences_count' => $timeline->where('type', 'presence')->count(),
            'achievements_count' => $timeline->where('type', 'achievement')->count(),
            'family_links_count' => $timeline->where('type', 'family_link')->count(),
            'finances_count' => $timeline->where('type', 'finance')->count(),
            'first_activity' => $timeline->last()['date'] ?? null,
            'latest_activity' => $timeline->first()['date'] ?? null,
        ];
    }

    /**
     * Get recent timeline (last 30 days).
     *
     * @param Person $person
     * @return Collection
     */
    public function getRecentTimeline(Person $person): Collection
    {
        return $this->buildByDateRange($person, now()->subDays(30), now());
    }
}
