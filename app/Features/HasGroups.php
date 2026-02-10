<?php

namespace App\Features;

use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HasGroups Trait
 *
 * Relacionamento de ministério com seus grupos.
 * Ministério pode ter N grupos (salas de aula, células, turmas).
 * Usado por: Ministry
 */
trait HasGroups
{
    /**
     * Get all groups for the ministry.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'ministry_id');
    }

    /**
     * Create a new group.
     *
     * @param array $data
     * @return Group
     */
    public function createGroup(array $data): Group
    {
        return $this->groups()->create($data);
    }

    /**
     * Get active groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeGroups()
    {
        return $this->groups()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get confidential groups.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function confidentialGroups()
    {
        return $this->groups()
            ->where('is_confidential', true)
            ->get();
    }

    /**
     * Get groups count.
     *
     * @return int
     */
    public function groupsCount(): int
    {
        return $this->groups()->count();
    }

    /**
     * Get total active members across all groups.
     *
     * @return int
     */
    public function totalGroupMembers(): int
    {
        return $this->groups->sum(function ($group) {
            return $group->activeMembersCount();
        });
    }

    /**
     * Get group by slug.
     *
     * @param string $slug
     * @return Group|null
     */
    public function groupBySlug(string $slug): ?Group
    {
        return $this->groups()->where('slug', $slug)->first();
    }
}
