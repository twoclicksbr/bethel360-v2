<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * AuthorizedPickup
 *
 * Representa pessoas autorizadas a retirar crianças.
 * Lista permanente cadastrada pelo responsável.
 * Protocolo de segurança inegociável: QR Code valida autorização na saída.
 * Não autorizado: bloqueio total + alerta ao responsável + liderança acionada.
 */
class AuthorizedPickup extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authorized_pickups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'authorized_person_id',
        'authorized_name',
        'relationship_id',
        'is_active',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the child.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'child_id');
    }

    /**
     * Get the authorized person (if registered in system).
     */
    public function authorizedPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'authorized_person_id');
    }

    /**
     * Get the relationship type.
     */
    public function relationship(): BelongsTo
    {
        return $this->belongsTo(Relationship::class);
    }

    /**
     * Get all contacts for this authorized pickup.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get all documents for this authorized pickup.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
