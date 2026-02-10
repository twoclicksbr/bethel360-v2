<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\ServiceAssignment;
use App\Services\ServiceRequestService;
use App\Http\Resources\V2\ServiceAssignmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceAssignmentController extends Controller
{
    protected ServiceRequestService $serviceRequestService;

    public function __construct(ServiceRequestService $serviceRequestService)
    {
        $this->serviceRequestService = $serviceRequestService;
    }

    /**
     * Create service assignment.
     *
     * POST /service-assignments
     * Body: { service_request_id, person_id, ministry_id, role_id, scheduled_at }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'service_request_id' => 'required|exists:service_requests,id',
            'person_id' => 'required|exists:people,id',
            'ministry_id' => 'required|exists:ministries,id',
            'role_id' => 'required|exists:roles,id',
            'scheduled_at' => 'required|date',
        ]);

        try {
            $assignment = $this->serviceRequestService->assignPerson($request->all());

            return response()->json([
                'data' => new ServiceAssignmentResource($assignment),
                'message' => 'Service assignment created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Respond to service assignment (accept or decline).
     *
     * PATCH /service-assignments/{serviceAssignment}/respond
     * Body: { accept: true/false, reason? }
     */
    public function respond(ServiceAssignment $serviceAssignment, Request $request): JsonResponse
    {
        $request->validate([
            'accept' => 'required|boolean',
            'reason' => 'nullable|string',
        ]);

        try {
            $serviceAssignment = $this->serviceRequestService->respondAssignment(
                $serviceAssignment,
                $request->accept,
                $request->reason
            );

            $message = $request->accept
                ? 'Service assignment accepted'
                : 'Service assignment declined';

            return response()->json([
                'data' => new ServiceAssignmentResource($serviceAssignment),
                'message' => $message,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
