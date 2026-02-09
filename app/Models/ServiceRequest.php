<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * ServiceRequest
 *
 * Representa solicitações de serviço/voluntariado entre ministérios.
 * Um ministério solicita voluntários de outro ministério para um evento.
 * Exemplo: Louvor solicita técnicos de som do ministério de Mídia.
 */
class ServiceRequest extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ministry_id',
        'event_id',
        'requesting_ministry_id',
        'description',
        'volunteers_needed',
        'needed_at',
        'status_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'volunteers_needed' => 'integer',
        'needed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the ministry that will provide volunteers.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }

    /**
     * Get the ministry that requested volunteers.
     */
    public function requestingMinistry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class, 'requesting_ministry_id');
    }

    /**
     * Get the event for this service request.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get all service assignments for this request.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ServiceAssignment::class);
    }
}
