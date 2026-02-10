<?php

namespace App\Features;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HasAchievements Trait
 *
 * Conquistas espirituais (marcos da jornada).
 * Conquistas são chaves que abrem portas (habilitam próximos passos).
 * Título montado automaticamente com base em is_achievement das features.
 * Usado por: Person
 */
trait HasAchievements
{
    /**
     * Get all achievements for the person.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'person_id');
    }

    /**
     * Check if person has completed a specific ministry.
     *
     * @param int $ministryId
     * @return bool
     */
    public function hasCompletedMinistry(int $ministryId): bool
    {
        return $this->achievements()
            ->where('ministry_id', $ministryId)
            ->exists();
    }

    /**
     * Check if person has completed a specific group.
     *
     * @param int $groupId
     * @return bool
     */
    public function hasCompletedGroup(int $groupId): bool
    {
        return $this->achievements()
            ->where('group_id', $groupId)
            ->exists();
    }

    /**
     * Check if person has completed ministry OR group.
     *
     * @param int|null $ministryId
     * @param int|null $groupId
     * @return bool
     */
    public function hasCompleted(?int $ministryId = null, ?int $groupId = null): bool
    {
        $query = $this->achievements();

        if ($ministryId) {
            $query->where('ministry_id', $ministryId);
        }

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->exists();
    }

    /**
     * Grant achievement (called when ministry/group is completed).
     *
     * @param int|null $ministryId
     * @param int|null $groupId
     * @return Achievement
     */
    public function grantAchievement(?int $ministryId = null, ?int $groupId = null): Achievement
    {
        // Monta título automaticamente
        $title = $this->buildAchievementTitle($ministryId, $groupId);

        return $this->achievements()->create([
            'ministry_id' => $ministryId,
            'group_id' => $groupId,
            'title' => $title,
            'achieved_at' => now(),
        ]);
    }

    /**
     * Build achievement title based on ministry/group.
     *
     * @param int|null $ministryId
     * @param int|null $groupId
     * @return string
     */
    private function buildAchievementTitle(?int $ministryId, ?int $groupId): string
    {
        if ($groupId) {
            $group = \App\Models\Group::find($groupId);
            return "Concluiu {$group->name}";
        }

        if ($ministryId) {
            $ministry = \App\Models\Ministry::find($ministryId);
            return "Concluiu {$ministry->name}";
        }

        return 'Conquista';
    }

    /**
     * Get recent achievements (last 12 months).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentAchievements()
    {
        return $this->achievements()
            ->where('achieved_at', '>=', now()->subYear())
            ->orderBy('achieved_at', 'desc')
            ->get();
    }

    /**
     * Get achievements count.
     *
     * @return int
     */
    public function achievementsCount(): int
    {
        return $this->achievements()->count();
    }
}
