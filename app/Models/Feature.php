<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Feature
 *
 * Representa as features/características que definem as regras dos ministérios e grupos
 * (gênero, faixa etária, modalidade, ciclo, mobilidade, perfil, capacidade, pré-requisitos).
 * Features são as "regras dos planetas" que determinam quem pode participar.
 */
class Feature extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'options',
        'is_achievement',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'is_achievement' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get all ministries using this feature.
     */
    public function ministries(): BelongsToMany
    {
        return $this->belongsToMany(Ministry::class, 'ministry_features')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get all groups using this feature.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_features')
            ->withPivot('value')
            ->withTimestamps();
    }
}
