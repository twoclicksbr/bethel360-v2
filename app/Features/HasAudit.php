<?php

namespace App\Features;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

/**
 * HasAudit Trait
 *
 * Registra automaticamente todas as alterações (created, updated, deleted) em audit_logs.
 * Captura: person_id, action, model, model_id, changes (before/after), ip, response
 */
trait HasAudit
{
    /**
     * Boot the trait and register model events.
     */
    public static function bootHasAudit(): void
    {
        static::created(function ($model) {
            $model->auditLog('created');
        });

        static::updated(function ($model) {
            $model->auditLog('updated');
        });

        static::deleted(function ($model) {
            $model->auditLog('deleted');
        });
    }

    /**
     * Create an audit log entry.
     *
     * @param string $action
     * @return void
     */
    protected function auditLog(string $action): void
    {
        // TODO ETAPA 3: Habilitar auditoria completa
        // Por enquanto, desabilitado para ETAPA 1
        return;

        $changes = [];

        if ($action === 'updated') {
            $changes = [
                'before' => $this->getOriginal(),
                'after' => $this->getAttributes(),
            ];
        } elseif ($action === 'created') {
            $changes = [
                'after' => $this->getAttributes(),
            ];
        } elseif ($action === 'deleted') {
            $changes = [
                'before' => $this->getOriginal(),
            ];
        }

        try {
            AuditLog::create([
                'person_id' => request()->person?->id ?? auth()->user()?->person_id ?? null,
                'action' => $action,
                'model' => get_class($this),
                'model_id' => $this->id,
                'changes' => $changes,
                'ip' => Request::ip(),
                'response' => null, // Será preenchido em casos específicos
            ]);
        } catch (\Exception $e) {
            // Silenciosamente ignora erros de auditoria para não bloquear operações
            logger()->warning('Erro ao criar audit log: ' . $e->getMessage());
        }
    }
}
