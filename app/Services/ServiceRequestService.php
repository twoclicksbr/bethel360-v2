<?php

namespace App\Services;

use App\Models\ServiceRequest;
use App\Models\ServiceAssignment;
use App\Models\Person;
use App\Models\Ministry;

/**
 * ServiceRequestService
 *
 * Gerencia convocações de ministérios e escalas de voluntários.
 * Aceite/recusa de service requests e assignments.
 */
class ServiceRequestService
{
    /**
     * Create service request (ministry calling for volunteers).
     *
     * @param array $data
     * @return ServiceRequest
     */
    public function createRequest(array $data): ServiceRequest
    {
        $serviceRequest = ServiceRequest::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // TODO ETAPA 3: Dispatch event ServiceRequestCreated
        // event(new ServiceRequestCreated($serviceRequest));

        return $serviceRequest;
    }

    /**
     * Respond to service request (person accepts or declines).
     *
     * @param ServiceRequest $serviceRequest
     * @param Person $person
     * @param bool $accept
     * @param string|null $reason
     * @return ServiceRequest
     */
    public function respondRequest(ServiceRequest $serviceRequest, Person $person, bool $accept, ?string $reason = null): ServiceRequest
    {
        if ($accept) {
            $serviceRequest->status = 'accepted';
            $serviceRequest->accepted_at = now();
            $serviceRequest->accepted_by = $person->id;
        } else {
            $serviceRequest->status = 'declined';
            $serviceRequest->declined_at = now();
            $serviceRequest->declined_by = $person->id;
            $serviceRequest->decline_reason = $reason;
        }

        $serviceRequest->save();

        // TODO ETAPA 3: Dispatch event ServiceRequestResponded
        // event(new ServiceRequestResponded($serviceRequest, $accept));

        return $serviceRequest;
    }

    /**
     * Assign person to serve (create assignment).
     *
     * @param array $data
     * @return ServiceAssignment
     */
    public function assignPerson(array $data): ServiceAssignment
    {
        $assignment = ServiceAssignment::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // TODO ETAPA 3: Dispatch event ServiceAssigned
        // event(new ServiceAssigned($assignment));

        return $assignment;
    }

    /**
     * Respond to service assignment (person accepts or declines).
     *
     * @param ServiceAssignment $assignment
     * @param bool $accept
     * @param string|null $reason
     * @return ServiceAssignment
     */
    public function respondAssignment(ServiceAssignment $assignment, bool $accept, ?string $reason = null): ServiceAssignment
    {
        if ($accept) {
            $assignment->status = 'accepted';
            $assignment->accepted_at = now();
        } else {
            $assignment->status = 'declined';
            $assignment->declined_at = now();
            $assignment->decline_reason = $reason;
        }

        $assignment->save();

        // TODO ETAPA 3: Dispatch event ServiceAssignmentResponded
        // event(new ServiceAssignmentResponded($assignment, $accept));

        return $assignment;
    }

    /**
     * Get service requests for ministry.
     *
     * @param Ministry $ministry
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRequestsByMinistry(Ministry $ministry, array $filters = [])
    {
        $query = ServiceRequest::where('requesting_ministry_id', $ministry->id);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get service assignments for person.
     *
     * @param Person $person
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssignmentsByPerson(Person $person, array $filters = [])
    {
        $query = ServiceAssignment::where('person_id', $person->id);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('scheduled_at', 'desc')->get();
    }

    /**
     * Cancel service request.
     *
     * @param ServiceRequest $serviceRequest
     * @return ServiceRequest
     */
    public function cancelRequest(ServiceRequest $serviceRequest): ServiceRequest
    {
        $serviceRequest->status = 'cancelled';
        $serviceRequest->save();

        return $serviceRequest;
    }

    /**
     * Cancel service assignment.
     *
     * @param ServiceAssignment $assignment
     * @return ServiceAssignment
     */
    public function cancelAssignment(ServiceAssignment $assignment): ServiceAssignment
    {
        $assignment->status = 'cancelled';
        $assignment->save();

        return $assignment;
    }
}
