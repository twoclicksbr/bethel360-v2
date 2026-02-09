<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Note
 *
 * Nota/anotação polimórfica que pode ser associada a Campus, Ministry, Group, Event, etc.
 * Pode ter um autor (person_id) e níveis de privacidade.
 */
class Note extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'noteable_type',
        'noteable_id',
        'person_id',
        'content',
        'is_private',
        'is_confidential',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'is_confidential' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent noteable model (Campus, Ministry, Group, Event, etc.).
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the person who created this note.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
