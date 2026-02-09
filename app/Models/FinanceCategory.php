<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * FinanceCategory
 *
 * Representa as categorias de movimentação financeira
 * (Dízimo, Oferta, Oferta Farol, Despesa Operacional, etc.).
 */
class FinanceCategory extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'finance_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all finances in this category.
     */
    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }
}
