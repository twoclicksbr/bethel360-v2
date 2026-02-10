<?php

namespace App\Features;

use App\Models\Document;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasDocuments Trait
 *
 * Adiciona relacionamento polimórfico de documentos a qualquer model.
 * Usado por: Person, AuthorizedPickup
 */
trait HasDocuments
{
    /**
     * Get all documents for the model.
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    /**
     * Add a new document to the model.
     *
     * @param array $data
     * @return Document
     */
    public function addDocument(array $data): Document
    {
        return $this->documents()->create($data);
    }

    /**
     * Get documents by type.
     *
     * @param int $typeDocumentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function documentsByType(int $typeDocumentId)
    {
        return $this->documents()->where('type_document_id', $typeDocumentId)->get();
    }

    /**
     * Get verified documents only.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function verifiedDocuments()
    {
        return $this->documents()->where('is_verified', true)->get();
    }

    /**
     * Get expired documents.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function expiredDocuments()
    {
        return $this->documents()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();
    }

    /**
     * Get documents expiring soon (within 30 days).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function expiringSoonDocuments()
    {
        return $this->documents()
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(30)])
            ->get();
    }

    /**
     * Get CPF document.
     *
     * @return Document|null
     */
    public function cpfDocument(): ?Document
    {
        return $this->documents()
            ->whereHas('typeDocument', function ($query) {
                $query->where('slug', 'cpf');
            })
            ->first();
    }

    /**
     * Get RG document.
     *
     * @return Document|null
     */
    public function rgDocument(): ?Document
    {
        return $this->documents()
            ->whereHas('typeDocument', function ($query) {
                $query->where('slug', 'rg');
            })
            ->first();
    }
}
