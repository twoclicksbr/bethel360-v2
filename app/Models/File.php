<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * File
 *
 * Arquivo polimórfico que pode ser associado a Campus, Ministry, Group, etc.
 * Armazena documentos, imagens, PDFs, etc.
 */
class File extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'name',
        'original_name',
        'path',
        'mime_type',
        'size',
        'disk',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the parent fileable model (Campus, Ministry, Group, etc.).
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
