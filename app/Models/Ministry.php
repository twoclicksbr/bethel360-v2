<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Ministry
 *
 * Representa um ministério dentro de um campus.
 * Ministérios são as áreas de atuação da igreja (30 Semanas, Louvor, GDC, Kids, etc.).
 * Regra absoluta: Se tem atividade, tem ministério.
 */
class Ministry extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ministries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campus_id',
        'name',
        'slug',
        'description',
        'template',
        'is_confidential',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_confidential' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the campus that owns this ministry.
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get all groups for this ministry.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get all features for this ministry.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'ministry_features')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get all people enrolled in this ministry.
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'ministry_persons')
            ->withPivot('role_id', 'status_id', 'enrolled_at', 'completed_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all events for this ministry.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get all presences in this ministry.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get all finances for this ministry.
     */
    public function finances(): HasMany
    {
        return $this->hasMany(Finance::class);
    }

    /**
     * Get all service requests made by this ministry.
     */
    public function requestedServices(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'requesting_ministry_id');
    }

    /**
     * Get all service requests targeting this ministry.
     */
    public function receivedServices(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'ministry_id');
    }

    /**
     * Get all addresses for this ministry.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get all contacts for this ministry.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get all files for this ministry.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Get all notes for this ministry.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get all achievements from this ministry.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }
}
