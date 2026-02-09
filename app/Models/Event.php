<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Event
 *
 * Representa eventos/atividades agendadas.
 * Não existe agenda sem ministério - evento sempre pertence a ministério ou grupo.
 * Pode ser recorrente (RRULE format).
 * Suporta localização física ou online (meeting_url).
 */
class Event extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ministry_id',
        'group_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'location',
        'meeting_url',
        'is_recurring',
        'recurrence_rule',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_recurring' => 'boolean',
        'is_public' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the ministry that owns this event.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get the group that owns this event.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get all presences for this event.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get all service requests for this event.
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get all notes for this event.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
