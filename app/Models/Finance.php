<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Finance
 *
 * Representa movimentações financeiras (entradas e saídas).
 * Entradas: Dízimo, Oferta, Oferta Farol.
 * Saídas: Despesas operacionais, etc.
 * Integração futura com Banco Asaas (PIX, boletos, cartão).
 */
class Finance extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finances';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'ministry_id',
        'finance_type_id',
        'finance_category_id',
        'payment_method_id',
        'amount',
        'description',
        'external_id',
        'status_id',
        'processed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the person related to this finance.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the ministry related to this finance.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get the finance type.
     */
    public function financeType(): BelongsTo
    {
        return $this->belongsTo(FinanceType::class);
    }

    /**
     * Get the finance category.
     */
    public function financeCategory(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class);
    }

    /**
     * Get the payment method.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Get the status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
