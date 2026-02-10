<?php

namespace App\Features;

use App\Models\Finance;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * HasFinances Trait
 *
 * Movimentações financeiras (dízimo, oferta, oferta farol).
 * Integração com Asaas (ETAPA 3).
 * Usado por: Person, Ministry
 */
trait HasFinances
{
    /**
     * Get all finances for the model.
     */
    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class, 'person_id');
    }

    /**
     * Create a new finance entry.
     *
     * @param array $data
     * @return Finance
     */
    public function addFinance(array $data): Finance
    {
        return $this->finances()->create($data);
    }

    /**
     * Get finances by type.
     *
     * @param int $financeTypeId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function financesByType(int $financeTypeId)
    {
        return $this->finances()
            ->where('finance_type_id', $financeTypeId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get finances by category.
     *
     * @param int $financeCategoryId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function financesByCategory(int $financeCategoryId)
    {
        return $this->finances()
            ->where('finance_category_id', $financeCategoryId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get finances by date range.
     *
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function financesByDateRange($startDate, $endDate)
    {
        return $this->finances()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total finances amount.
     *
     * @return float
     */
    public function totalFinances(): float
    {
        return $this->finances()->sum('amount');
    }

    /**
     * Get total finances by type.
     *
     * @param int $financeTypeId
     * @return float
     */
    public function totalFinancesByType(int $financeTypeId): float
    {
        return $this->finances()
            ->where('finance_type_id', $financeTypeId)
            ->sum('amount');
    }

    /**
     * Get pending finances (not confirmed).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pendingFinances()
    {
        return $this->finances()
            ->where(function ($query) {
                $query->whereNull('confirmed_at')
                    ->orWhere('status', 'pending');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get confirmed finances.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function confirmedFinances()
    {
        return $this->finances()
            ->whereNotNull('confirmed_at')
            ->where('status', 'confirmed')
            ->orderBy('confirmed_at', 'desc')
            ->get();
    }

    /**
     * Get recent finances (last 12 months).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentFinances()
    {
        return $this->finances()
            ->where('created_at', '>=', now()->subYear())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
