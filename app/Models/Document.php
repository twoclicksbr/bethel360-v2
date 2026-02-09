<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Document
 *
 * Documento polimórfico que pode ser associado a Person, AuthorizedPickup, etc.
 * Armazena CPF, RG, CNH, certidões, etc.
 */
class Document extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'type_document_id',
        'number',
        'issuer',
        'issue_date',
        'expires_at',
        'is_verified',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'issue_date' => 'date',
        'expires_at' => 'date',
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent documentable model (Person, AuthorizedPickup, etc.).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the type of this document.
     */
    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }
}
