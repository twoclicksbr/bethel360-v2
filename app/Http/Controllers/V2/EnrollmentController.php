<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Ministry;
use App\Models\Group;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Enroll person in ministry.
     *
     * POST /enrollment/ministry
     * Body: { person_id, ministry_id, role_id }
     */
    public function enrollMinistry(Request $request): JsonResponse
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'ministry_id' => 'required|exists:ministries,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $person = Person::findOrFail($request->person_id);
            $ministry = Ministry::findOrFail($request->ministry_id);

            $enrollment = $this->enrollmentService->enrollInMinistry(
                $person,
                $ministry,
                $request->role_id
            );

            return response()->json([
                'data' => [
                    'id' => $enrollment->id,
                    'person_id' => $enrollment->person_id,
                    'ministry_id' => $enrollment->ministry_id,
                    'role_id' => $enrollment->role_id,
                    'status_id' => $enrollment->status_id,
                    'enrolled_at' => $enrollment->enrolled_at,
                ],
                'message' => 'Person successfully enrolled in ministry',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Enroll person in group.
     *
     * POST /enrollment/group
     * Body: { person_id, group_id, role_id }
     */
    public function enrollGroup(Request $request): JsonResponse
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'group_id' => 'required|exists:groups,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $person = Person::findOrFail($request->person_id);
            $group = Group::findOrFail($request->group_id);

            $enrollment = $this->enrollmentService->enrollInGroup(
                $person,
                $group,
                $request->role_id
            );

            return response()->json([
                'data' => [
                    'id' => $enrollment->id,
                    'person_id' => $enrollment->person_id,
                    'group_id' => $enrollment->group_id,
                    'role_id' => $enrollment->role_id,
                    'status_id' => $enrollment->status_id,
                    'enrolled_at' => $enrollment->enrolled_at,
                ],
                'message' => 'Person successfully enrolled in group',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
