<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Finance;

/**
 * FinanceService
 *
 * Registra movimentações financeiras (dízimo, oferta, oferta farol).
 * Integração com Asaas (ETAPA 3).
 */
class FinanceService
{
    /**
     * Create finance entry.
     *
     * @param array $data
     * @return Finance
     */
    public function create(array $data): Finance
    {
        // TODO ETAPA 3: Integrate with Asaas
        // - Generate payment link
        // - Create charge in Asaas
        // - Store external_id

        $finance = Finance::create(array_merge($data, [
            'status' => 'pending',
        ]));

        // TODO ETAPA 3: Dispatch event FinanceCreated
        // event(new FinanceCreated($finance));

        return $finance;
    }

    /**
     * Confirm finance (callback from Asaas).
     *
     * @param Finance $finance
     * @return Finance
     */
    public function confirm(Finance $finance): Finance
    {
        $finance->status = 'confirmed';
        $finance->confirmed_at = now();
        $finance->save();

        // TODO ETAPA 3: Dispatch event FinanceReceived
        // event(new FinanceReceived($finance));

        return $finance;
    }

    /**
     * Cancel finance.
     *
     * @param Finance $finance
     * @return Finance
     */
    public function cancel(Finance $finance): Finance
    {
        $finance->status = 'cancelled';
        $finance->save();

        // TODO ETAPA 3: Cancel charge in Asaas if exists

        return $finance;
    }

    /**
     * Get finance history for person.
     *
     * @param Person $person
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHistory(Person $person, array $filters = [])
    {
        $query = $person->finances()->with(['financeType', 'financeCategory', 'paymentMethod']);

        // Filter by type
        if (isset($filters['finance_type_id'])) {
            $query->where('finance_type_id', $filters['finance_type_id']);
        }

        // Filter by category
        if (isset($filters['finance_category_id'])) {
            $query->where('finance_category_id', $filters['finance_category_id']);
        }

        // Filter by status
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by date range
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get totals by type.
     *
     * @param Person $person
     * @return array
     */
    public function getTotalsByType(Person $person): array
    {
        $finances = $person->finances()
            ->where('status', 'confirmed')
            ->with('financeType')
            ->get();

        $totals = [];

        foreach ($finances->groupBy('finance_type_id') as $typeId => $items) {
            $type = $items->first()->financeType;

            $totals[] = [
                'type_id' => $typeId,
                'type_name' => $type->name ?? 'Unknown',
                'total' => $items->sum('amount'),
                'count' => $items->count(),
            ];
        }

        return $totals;
    }

    /**
     * Get totals by month (last 12 months).
     *
     * @param Person $person
     * @return array
     */
    public function getTotalsByMonth(Person $person): array
    {
        $finances = $person->finances()
            ->where('status', 'confirmed')
            ->where('confirmed_at', '>=', now()->subYear())
            ->get();

        $byMonth = [];

        foreach ($finances as $finance) {
            $monthKey = $finance->confirmed_at->format('Y-m');

            if (!isset($byMonth[$monthKey])) {
                $byMonth[$monthKey] = [
                    'month' => $monthKey,
                    'total' => 0,
                    'count' => 0,
                ];
            }

            $byMonth[$monthKey]['total'] += $finance->amount;
            $byMonth[$monthKey]['count']++;
        }

        return array_values($byMonth);
    }
}
