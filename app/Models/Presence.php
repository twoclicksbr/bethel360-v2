<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Presence
 *
 * Representa o registro de presença de uma pessoa em um evento, grupo ou ministério.
 * Presença é AUTOMÁTICA - ninguém marca manualmente.
 * Grupo grande (cultos): QR Code no telão → pessoa escaneia.
 * Grupo pequeno (células): Líder escaneia QR Code da pessoa.
 * Online (Meet): Sistema sabe tempo de permanência.
 * Fallback: Manual por email/código se necessário.
 * Dois tipos: Participante (recebendo) ou Servindo (escalado).
 */
class Presence extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'presences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'event_id',
        'group_id',
        'ministry_id',
        'role_id',
        'presence_method_id',
        'registered_at',
        'checked_out_at',
        'duration_minutes',
        'is_serving',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'duration_minutes' => 'integer',
        'is_serving' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the person who was present.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the event.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the group.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the ministry.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get the role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the presence method.
     */
    public function presenceMethod(): BelongsTo
    {
        return $this->belongsTo(PresenceMethod::class);
    }
}
