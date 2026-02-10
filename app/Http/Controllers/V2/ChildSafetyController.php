<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Services\ChildSafetyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChildSafetyController extends Controller
{
    protected ChildSafetyService $childSafetyService;

    public function __construct(ChildSafetyService $childSafetyService)
    {
        $this->childSafetyService = $childSafetyService;
    }

    /**
     * Validate if person is authorized to pick up child.
     *
     * POST /child-safety/validate
     * Body: { child_id, requester_id OR requester_name }
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'child_id' => 'required|exists:people,id',
            'requester_id' => 'nullable|exists:people,id',
            'requester_name' => 'nullable|string',
        ]);

        if (!$request->requester_id && !$request->requester_name) {
            return response()->json([
                'message' => 'Either requester_id or requester_name must be provided',
            ], 422);
        }

        try {
            $result = $this->childSafetyService->validatePickup(
                $request->child_id,
                $request->requester_id,
                $request->requester_name
            );

            $statusCode = $result['authorized'] ? 200 : 403;

            return response()->json($result, $statusCode);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
