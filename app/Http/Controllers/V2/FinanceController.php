<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Services\FinanceService;
use App\Http\Resources\V2\FinanceResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FinanceController extends Controller
{
    protected FinanceService $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    /**
     * Create finance entry.
     *
     * POST /finances
     * Body: { person_id, ministry_id?, finance_type_id, finance_category_id, payment_method_id, amount }
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'person_id' => 'required|exists:people,id',
            'ministry_id' => 'nullable|exists:ministries,id',
            'finance_type_id' => 'required|exists:finance_types,id',
            'finance_category_id' => 'required|exists:finance_categories,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $finance = $this->financeService->create($request->all());

            return response()->json([
                'data' => new FinanceResource($finance),
                'message' => 'Finance entry created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get finance history for person.
     *
     * GET /people/{person}/finances
     * Query: ?finance_type_id=1&status=confirmed&start_date=2024-01-01&end_date=2024-12-31
     */
    public function history(Person $person, Request $request)
    {
        $request->validate([
            'finance_type_id' => 'nullable|exists:finance_types,id',
            'finance_category_id' => 'nullable|exists:finance_categories,id',
            'status' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $filters = $request->only([
            'finance_type_id',
            'finance_category_id',
            'status',
            'start_date',
            'end_date',
        ]);

        $finances = $this->financeService->getHistory($person, $filters);

        return FinanceResource::collection($finances);
    }
}
