<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ServiceAssignment
 *
 * Representa a alocação de uma pessoa específica para um ServiceRequest.
 * Voluntário pode aceitar ou recusar a convocação.
 * Presença do voluntário cai no ministério dele (is_serving = true).
 */
class ServiceAssignment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_request_id',
        'person_id',
        'assigned_by',
        'status_id',
        'assigned_at',
        'responded_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_at' => 'datetime',
        'responded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the service request.
     */
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    /**
     * Get the person assigned.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Get the person who made the assignment.
     */
    public function assignedByPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'assigned_by');
    }

    /**
     * Get the status.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
