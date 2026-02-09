<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Group
 *
 * Representa um grupo dentro de um ministério.
 * Grupos são subdivisões de ministérios (sala de aula, célula, turma, equipe).
 * Herda features do ministério pai, podendo apenas adicionar restrições.
 */
class Group extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ministry_id',
        'name',
        'slug',
        'description',
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
     * Get the ministry that owns this group.
     */
    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    /**
     * Get all features for this group.
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'group_features')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get all people enrolled in this group.
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'group_persons')
            ->withPivot('role_id', 'status_id', 'enrolled_at', 'completed_at', 'notes')
            ->withTimestamps();
    }

    /**
     * Get all events for this group.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get all presences in this group.
     */
    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

    /**
     * Get all addresses for this group.
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get all contacts for this group.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get all files for this group.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Get all notes for this group.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get all achievements from this group.
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }
}
