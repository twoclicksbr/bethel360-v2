<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * AuditLog
 *
 * Registro permanente de auditoria de todas as ações no sistema.
 * NÃO usa SoftDeletes (permanente).
 * NÃO tem updated_at (apenas created_at).
 * Captura person_id, action, model, changes (before/after), ip, response.
 */
class AuditLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'action',
        'model',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
        'request_url',
        'response_code',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'changes' => 'array',
        'response_code' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Get the person who performed this action.
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
