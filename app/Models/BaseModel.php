<?php

namespace App\Models;

use App\Features\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * BaseModel
 *
 * Modelo base que todos os models da aplicação devem herdar.
 * Fornece SoftDeletes e auditoria automática.
 */
abstract class BaseModel extends Model
{
    use SoftDeletes, HasAudit;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
