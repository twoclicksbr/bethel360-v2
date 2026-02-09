<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Person
 *
 * Representa uma pessoa no sistema. É o cadastro único dentro do tenant.
 * Person pertence ao Tenant, não ao campus.
 * E-mail (via contacts) é a chave que conecta tudo.
 * QR Code é o ID formatado em 6 dígitos (id 42 → "000042").
 */
class Person extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender_id',
        'birth_date',
        'photo_url',
        'qr_code',
        'is_child',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'is_child' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_name'];

    /**
     * Get the person's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name . ' ' . ($this->last_name ?? ''))
        );
    }

    /**
     * Get the gender of this person.
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * Get the user account for this person (if exists).
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get all ministries this person is enrolled in.
     */
    public function ministries(): BelongsToMany
    {
        return $this->belongsToMany(Ministry::class, 'ministry_persons')
            ->withPivot('role_id', 'status_id', 'enrolled_at', 'completed_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all groups this person is enrolled in.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_persons')
            ->withPivot('role_id', 'status_id', 'enrolled_at', 'completed_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all presences for this person.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get all achievements for this person.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Get all finances for this person.
     */
    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }

    /**
     * Get all family links where this person is the subject.
     */
    public function familyLinks(): HasMany
    {
        return $this->hasMany(FamilyLink::class, 'person_id');
    }

    /**
     * Get all family links where this person is the related person.
     */
    public function reverseFamilyLinks(): HasMany
    {
        return $this->hasMany(FamilyLink::class, 'related_person_id');
    }

    /**
     * Get all authorized pickups if this person is a child.
     */
    public function authorizedPickups(): HasMany
    {
        return $this->hasMany(AuthorizedPickup::class, 'child_id');
    }

    /**
     * Get all children this person is authorized to pick up.
     */
    public function authorizedFor(): HasMany
    {
        return $this->hasMany(AuthorizedPickup::class, 'authorized_person_id');
    }

    /**
     * Get all service assignments for this person.
     */
    public function serviceAssignments(): HasMany
    {
        return $this->hasMany(ServiceAssignment::class);
    }

    /**
     * Get all service assignments made by this person.
     */
    public function assignedServices(): HasMany
    {
        return $this->hasMany(ServiceAssignment::class, 'assigned_by');
    }

    /**
     * Get all audit logs for this person.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get all notes authored by this person.
     */
    public function authoredNotes(): HasMany
    {
        return $this->hasMany(Note::class, 'person_id');
    }

    /**
     * Get all addresses for this person.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get all contacts for this person.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get all documents for this person.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
