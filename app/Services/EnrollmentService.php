<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Ministry;
use App\Models\Group;
use App\Models\MinistryPerson;
use App\Models\GroupPerson;
use App\Exceptions\InvalidEnrollmentException;
use App\Exceptions\PrerequisiteNotMetException;
use App\Exceptions\GroupFullException;
use Illuminate\Support\Facades\DB;

/**
 * EnrollmentService
 *
 * Valida features, capacidade, pré-requisitos e inscreve em ministry/group.
 * REGRA: Grupo herda features do ministério.
 */
class EnrollmentService
{
    /**
     * Enroll person in ministry.
     *
     * @param Person $person
     * @param Ministry $ministry
     * @param int $roleId
     * @return MinistryPerson
     * @throws InvalidEnrollmentException
     * @throws PrerequisiteNotMetException
     */
    public function enrollInMinistry(Person $person, Ministry $ministry, int $roleId): MinistryPerson
    {
        // 1. Validate features match
        if (!$ministry->matchesProfile($person)) {
            throw new InvalidEnrollmentException('Profile does not match ministry features');
        }

        // 2. Check prerequisites
        $this->validatePrerequisites($person, $ministry);

        // 3. Get active status
        $activeStatusId = $this->getActiveStatusId();

        // 4. Check if already enrolled
        if ($ministry->hasMember($person)) {
            throw new InvalidEnrollmentException('Person is already enrolled in this ministry');
        }

        // 5. Create MinistryPerson
        return $ministry->enroll($person, $roleId, $activeStatusId);
    }

    /**
     * Enroll person in group.
     *
     * @param Person $person
     * @param Group $group
     * @param int $roleId
     * @return GroupPerson
     * @throws InvalidEnrollmentException
     * @throws PrerequisiteNotMetException
     * @throws GroupFullException
     */
    public function enrollInGroup(Person $person, Group $group, int $roleId): GroupPerson
    {
        // 1. Validate group features (inherits from ministry + own restrictions)
        if (!$group->matchesProfile($person)) {
            throw new InvalidEnrollmentException('Profile does not match group features');
        }

        // 2. Check capacity
        $availableCapacity = $group->availableCapacity();

        if ($availableCapacity !== null && $availableCapacity <= 0) {
            throw new GroupFullException('Group has reached maximum capacity');
        }

        // 3. Check prerequisites
        $this->validatePrerequisites($person, $group);

        // 4. Get active status
        $activeStatusId = $this->getActiveStatusId();

        // 5. Check if already enrolled
        if ($group->hasMember($person)) {
            throw new InvalidEnrollmentException('Person is already enrolled in this group');
        }

        // 6. Create GroupPerson
        return $group->enroll($person, $roleId, $activeStatusId);
    }

    /**
     * Validate prerequisites for enrollment.
     *
     * @param Person $person
     * @param Ministry|Group $entity
     * @return void
     * @throws PrerequisiteNotMetException
     */
    private function validatePrerequisites(Person $person, $entity): void
    {
        $prerequisites = $entity->getPrerequisites();

        // Check ministry prerequisites
        foreach ($prerequisites['ministry_ids'] as $ministryId) {
            if (!$person->hasCompletedMinistry($ministryId)) {
                $ministry = Ministry::find($ministryId);
                $name = $ministry ? $ministry->name : "Ministry ID $ministryId";
                throw new PrerequisiteNotMetException("Person has not completed required prerequisite: $name");
            }
        }

        // Check group prerequisites
        foreach ($prerequisites['group_ids'] as $groupId) {
            if (!$person->hasCompletedGroup($groupId)) {
                $group = Group::find($groupId);
                $name = $group ? $group->name : "Group ID $groupId";
                throw new PrerequisiteNotMetException("Person has not completed required prerequisite: $name");
            }
        }
    }

    /**
     * Get active status ID.
     *
     * @return int
     */
    private function getActiveStatusId(): int
    {
        return DB::table('statuses')
            ->where('slug', 'ativo')
            ->where('module_id', function ($query) {
                $query->select('id')
                    ->from('modules')
                    ->where('slug', 'central-de-vidas')
                    ->limit(1);
            })
            ->value('id');
    }

    /**
     * Update enrollment status (e.g., mark as completed).
     *
     * @param MinistryPerson|GroupPerson $enrollment
     * @param int $statusId
     * @return void
     */
    public function updateEnrollmentStatus($enrollment, int $statusId): void
    {
        $enrollment->status_id = $statusId;

        // Se status é "concluído", marca completed_at
        $completedStatusId = DB::table('statuses')
            ->where('slug', 'concluido')
            ->where('module_id', function ($query) {
                $query->select('id')
                    ->from('modules')
                    ->where('slug', 'central-de-vidas')
                    ->limit(1);
            })
            ->value('id');

        if ($statusId === $completedStatusId) {
            $enrollment->completed_at = now();
        }

        $enrollment->save();
    }
}
