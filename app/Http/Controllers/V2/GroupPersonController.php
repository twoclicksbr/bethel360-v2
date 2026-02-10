<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Person;
use App\Services\EnrollmentService;
use App\Http\Resources\V2\PersonResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * GroupPersonController
 *
 * Nested route: /groups/{group}/people
 */
class GroupPersonController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * Get all people for a specific group.
     *
     * GET /groups/{group}/people
     */
    public function index(Group $group, Request $request)
    {
        $query = $group->people();

        // Filter by status
        if ($request->has('filter.status_id')) {
            $query->wherePivot('status_id', $request->input('filter.status_id'));
        }

        // Filter by role
        if ($request->has('filter.role_id')) {
            $query->wherePivot('role_id', $request->input('filter.role_id'));
        }

        // Includes
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100);

        $people = $query->paginate($perPage);

        return PersonResource::collection($people);
    }

    /**
     * Add person to group (enroll).
     *
     * POST /groups/{group}/people
     * Body: { person_id, role_id }
     */
    public function store(Group $group, Request $request): JsonResponse
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $person = Person::findOrFail($request->person_id);

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
                'message' => 'Person successfully added to group',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove person from group (soft delete enrollment).
     *
     * DELETE /groups/{group}/people/{person}
     */
    public function destroy(Group $group, Person $person): JsonResponse
    {
        $enrollment = \App\Models\GroupPerson::where('group_id', $group->id)
            ->where('person_id', $person->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'message' => 'Person is not enrolled in this group',
            ], 404);
        }

        $enrollment->delete();

        return response()->json(null, 204);
    }
}
