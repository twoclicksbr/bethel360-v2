<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\FamilyLink;
use App\Services\FamilyLinkService;
use App\Http\Resources\V2\FamilyLinkResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FamilyLinkController extends Controller
{
    protected FamilyLinkService $familyLinkService;

    public function __construct(FamilyLinkService $familyLinkService)
    {
        $this->familyLinkService = $familyLinkService;
    }

    /**
     * Request family link.
     *
     * POST /family-links
     * Body: { person_id, related_person_id, relationship_id }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'related_person_id' => 'required|exists:people,id|different:person_id',
            'relationship_id' => 'required|exists:relationships,id',
        ]);

        try {
            $person = \App\Models\Person::findOrFail($request->person_id);

            $familyLink = $this->familyLinkService->request(
                $person,
                $request->related_person_id,
                $request->relationship_id
            );

            return response()->json([
                'data' => new FamilyLinkResource($familyLink),
                'message' => 'Family link request sent successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Respond to family link request (accept or decline).
     *
     * PATCH /family-links/{familyLink}/respond
     * Body: { accept: true/false }
     */
    public function respond(FamilyLink $familyLink, Request $request): JsonResponse
    {
        $request->validate([
            'accept' => 'required|boolean',
        ]);

        try {
            $familyLink = $this->familyLinkService->respond(
                $familyLink,
                $request->accept
            );

            $message = $request->accept
                ? 'Family link accepted successfully'
                : 'Family link declined';

            return response()->json([
                'data' => new FamilyLinkResource($familyLink),
                'message' => $message,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
