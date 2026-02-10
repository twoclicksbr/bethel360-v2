<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Services\ServiceRequestService;
use App\Http\Resources\V2\ServiceRequestResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ServiceRequestController extends Controller
{
    protected ServiceRequestService $serviceRequestService;

    public function __construct(ServiceRequestService $serviceRequestService)
    {
        $this->serviceRequestService = $serviceRequestService;
    }

    /**
     * Create service request.
     *
     * POST /service-requests
     * Body: { requesting_ministry_id, target_ministry_id, description }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'requesting_ministry_id' => 'required|exists:ministries,id',
            'target_ministry_id' => 'required|exists:ministries,id',
            'description' => 'required|string',
        ]);

        try {
            $serviceRequest = $this->serviceRequestService->createRequest($request->all());

            return response()->json([
                'data' => new ServiceRequestResource($serviceRequest),
                'message' => 'Service request created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Respond to service request (accept or decline).
     *
     * PATCH /service-requests/{serviceRequest}/respond
     * Body: { accept: true/false, reason? }
     */
    public function respond(ServiceRequest $serviceRequest, Request $request): JsonResponse
    {
        $request->validate([
            'accept' => 'required|boolean',
            'person_id' => 'required|exists:people,id',
            'reason' => 'nullable|string',
        ]);

        try {
            $person = \App\Models\Person::findOrFail($request->person_id);

            $serviceRequest = $this->serviceRequestService->respondRequest(
                $serviceRequest,
                $person,
                $request->accept,
                $request->reason
            );

            $message = $request->accept
                ? 'Service request accepted'
                : 'Service request declined';

            return response()->json([
                'data' => new ServiceRequestResource($serviceRequest),
                'message' => $message,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
