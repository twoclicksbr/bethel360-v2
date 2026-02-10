<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Services\PresenceService;
use App\Http\Resources\V2\PresenceResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PresenceController extends Controller
{
    protected PresenceService $presenceService;

    public function __construct(PresenceService $presenceService)
    {
        $this->presenceService = $presenceService;
    }

    /**
     * Register presence (QR Code or manual).
     *
     * POST /presence/register
     * Body: { qr_code OR person_id, event_id, presence_method_id, role_id? }
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'qr_code' => 'required_without:person_id|string|size:6',
            'person_id' => 'required_without:qr_code|exists:people,id',
            'event_id' => 'required|exists:events,id',
            'presence_method_id' => 'required|exists:presence_methods,id',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        try {
            if ($request->has('qr_code')) {
                $presence = $this->presenceService->registerByQrCode(
                    $request->qr_code,
                    $request->event_id,
                    $request->presence_method_id,
                    $request->role_id
                );
            } else {
                $presence = $this->presenceService->registerManual(
                    $request->person_id,
                    $request->event_id,
                    $request->presence_method_id,
                    $request->role_id
                );
            }

            return response()->json([
                'data' => new PresenceResource($presence),
                'message' => 'Presence registered successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Register batch presences.
     *
     * POST /presence/batch
     * Body: { qr_codes: [], event_id, presence_method_id }
     */
    public function registerBatch(Request $request): JsonResponse
    {
        $request->validate([
            'qr_codes' => 'required|array',
            'qr_codes.*' => 'string|size:6',
            'event_id' => 'required|exists:events,id',
            'presence_method_id' => 'required|exists:presence_methods,id',
        ]);

        try {
            $result = $this->presenceService->registerBatch(
                $request->qr_codes,
                $request->event_id,
                $request->presence_method_id
            );

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
