<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * GroupPerson
 *
 * Pivot model que conecta Group e Person.
 * Representa o vínculo/participação de uma pessoa em um grupo.
 * Cada participação tem status independente (Ativo, Inativo, Pendente, Concluído).
 * Status = Concluído → pode gerar Achievement (conquista espiritual).
 */
class GroupPerson extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_persons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'person_id',
        'role_id',
        'status_id',
        'enrolled_at',
        'completed_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrolled_at' => 'date',
        'completed_at' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the group.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the person.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
