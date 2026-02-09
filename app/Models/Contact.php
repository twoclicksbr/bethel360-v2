<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Contact
 *
 * Contato polimórfico que pode ser associado a Campus, Ministry, Group, Person, AuthorizedPickup, etc.
 * Armazena emails, telefones, WhatsApp, etc.
 */
class Contact extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contactable_type',
        'contactable_id',
        'type_contact_id',
        'value',
        'is_primary',
        'is_verified',
        'verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent contactable model (Campus, Ministry, Group, Person, etc.).
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the type of this contact.
     */
    public function typeContact(): BelongsTo
    {
        return $this->belongsTo(TypeContact::class, 'type_contact_id');
    }
}
