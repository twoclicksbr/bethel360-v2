<?php

namespace App\Features;

use App\Models\Person;
use App\Models\MinistryPerson;
use App\Models\GroupPerson;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * HasMembers Trait
 *
 * Gerencia vínculos de pessoas em ministérios/grupos.
 * Conta membros ativos, inscreve, atualiza status.
 * Usado por: Ministry, Group
 */
trait HasMembers
{
    /**
     * Get ministry persons relationship.
     */
    public function ministryPersons(): HasMany
    {
        if ($this instanceof \App\Models\Ministry) {
            return $this->hasMany(MinistryPerson::class, 'ministry_id');
        }

        throw new \LogicException('ministryPersons() can only be called on Ministry model');
    }

    /**
     * Get group persons relationship.
     */
    public function groupPersons(): HasMany
    {
        if ($this instanceof Group) {
            return $this->hasMany(GroupPerson::class, 'group_id');
        }

        throw new \LogicException('groupPersons() can only be called on Group model');
    }

    /**
     * Get people relationship (through pivot).
     */
    public function people()
    {
        if ($this instanceof \App\Models\Ministry) {
            return $this->belongsToMany(
                Person::class,
                'ministry_persons',
                'ministry_id',
                'person_id'
            )->withPivot(['role_id', 'status_id', 'enrolled_at', 'completed_at'])
                ->withTimestamps();
        }

        if ($this instanceof Group) {
            return $this->belongsToMany(
                Person::class,
                'group_persons',
                'group_id',
                'person_id'
            )->withPivot(['role_id', 'status_id', 'enrolled_at', 'completed_at'])
                ->withTimestamps();
        }

        throw new \LogicException('people() can only be called on Ministry or Group model');
    }

    /**
     * Count active members.
     *
     * @return int
     */
    public function activeMembersCount(): int
    {
        // Busca status "ativo"
        $activeStatusId = DB::table('statuses')
            ->where('slug', 'ativo')
            ->where('module_id', function ($query) {
                $query->select('id')
                    ->from('modules')
                    ->where('slug', 'central-de-vidas')
                    ->limit(1);
            })
            ->value('id');

        if (!$activeStatusId) {
            return 0;
        }

        if ($this instanceof \App\Models\Ministry) {
            return $this->ministryPersons()
                ->where('status_id', $activeStatusId)
                ->count();
        }

        if ($this instanceof Group) {
            return $this->groupPersons()
                ->where('status_id', $activeStatusId)
                ->count();
        }

        return 0;
    }

    /**
     * Enroll a person.
     *
     * @param Person $person
     * @param int $roleId
     * @param int $statusId
     * @return MinistryPerson|GroupPerson
     */
    public function enroll(Person $person, int $roleId, int $statusId)
    {
        if ($this instanceof \App\Models\Ministry) {
            return MinistryPerson::create([
                'ministry_id' => $this->id,
                'person_id' => $person->id,
                'role_id' => $roleId,
                'status_id' => $statusId,
                'enrolled_at' => now(),
            ]);
        }

        if ($this instanceof Group) {
            return GroupPerson::create([
                'group_id' => $this->id,
                'person_id' => $person->id,
                'role_id' => $roleId,
                'status_id' => $statusId,
                'enrolled_at' => now(),
            ]);
        }

        throw new \LogicException('enroll() can only be called on Ministry or Group model');
    }

    /**
     * Update member status.
     *
     * @param Person $person
     * @param int $statusId
     * @return bool
     */
    public function updateMemberStatus(Person $person, int $statusId): bool
    {
        if ($this instanceof \App\Models\Ministry) {
            $updated = MinistryPerson::where('ministry_id', $this->id)
                ->where('person_id', $person->id)
                ->update(['status_id' => $statusId]);

            return $updated > 0;
        }

        if ($this instanceof Group) {
            $updated = GroupPerson::where('group_id', $this->id)
                ->where('person_id', $person->id)
                ->update(['status_id' => $statusId]);

            return $updated > 0;
        }

        return false;
    }

    /**
     * Check if person is enrolled (any status).
     *
     * @param Person $person
     * @return bool
     */
    public function hasMember(Person $person): bool
    {
        if ($this instanceof \App\Models\Ministry) {
            return $this->ministryPersons()
                ->where('person_id', $person->id)
                ->exists();
        }

        if ($this instanceof Group) {
            return $this->groupPersons()
                ->where('person_id', $person->id)
                ->exists();
        }

        return false;
    }

    /**
     * Check if person is active member.
     *
     * @param Person $person
     * @return bool
     */
    public function hasActiveMember(Person $person): bool
    {
        $activeStatusId = DB::table('statuses')
            ->where('slug', 'ativo')
            ->where('module_id', function ($query) {
                $query->select('id')
                    ->from('modules')
                    ->where('slug', 'central-de-vidas')
                    ->limit(1);
            })
            ->value('id');

        if (!$activeStatusId) {
            return false;
        }

        if ($this instanceof \App\Models\Ministry) {
            return $this->ministryPersons()
                ->where('person_id', $person->id)
                ->where('status_id', $activeStatusId)
                ->exists();
        }

        if ($this instanceof Group) {
            return $this->groupPersons()
                ->where('person_id', $person->id)
                ->where('status_id', $activeStatusId)
                ->exists();
        }

        return false;
    }
}
