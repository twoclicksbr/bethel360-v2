<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Relationship
 *
 * Representa os tipos de relacionamento familiar
 * (Cônjuge, Filho, Pai, Mãe, Irmão, Tio, Avó, etc.).
 */
class Relationship extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relationships';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'reciprocal_name',
        'reciprocal_slug',
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
     * Get all family links using this relationship.
     */
    public function familyLinks(): HasMany
    {
        return $this->hasMany(FamilyLink::class);
    }

    /**
     * Get all authorized pickups using this relationship.
     */
    public function authorizedPickups(): HasMany
    {
        return $this->hasMany(AuthorizedPickup::class);
    }
}
