<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GroupFeature
 *
 * Pivot model que conecta Group e Feature.
 * Armazena o valor específico da feature para aquele grupo.
 * IMPORTANTE: Grupo herda features do ministério. GroupFeature só adiciona restrições extras, nunca contradiz.
 */
class GroupFeature extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_features';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'feature_id',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the group that owns this feature.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the feature.
     */
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}
