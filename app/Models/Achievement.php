<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Achievement
 *
 * Representa conquistas espirituais da pessoa.
 * Não são só registros - são chaves que abrem portas.
 * Concluir 30 Semanas → habilita ser confidente.
 * Concluir Rota do Conhecimento → habilita liderar célula.
 * Concluir nível 1 → libera nível 2.
 * Título montado automaticamente com base em is_achievement das features.
 */
class Achievement extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'achievements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'ministry_id',
        'group_id',
        'title',
        'description',
        'achieved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'achieved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the person who earned this achievement.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the ministry related to this achievement.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get the group related to this achievement.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
