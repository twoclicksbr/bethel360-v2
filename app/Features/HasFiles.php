<?php

namespace App\Features;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasFiles Trait
 *
 * Adiciona relacionamento polimórfico de arquivos a qualquer model.
 * Usado por: Campus, Ministry, Group
 */
trait HasFiles
{
    /**
     * Get all files for the model.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    /**
     * Add a new file to the model.
     *
     * @param array $data
     * @return File
     */
    public function addFile(array $data): File
    {
        return $this->files()->create($data);
    }

    /**
     * Get files by type (mime_type).
     *
     * @param string $mimeType
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filesByMimeType(string $mimeType)
    {
        return $this->files()->where('mime_type', 'LIKE', "$mimeType%")->get();
    }

    /**
     * Get image files.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function imageFiles()
    {
        return $this->filesByMimeType('image');
    }

    /**
     * Get document files (PDF, Word, etc.).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function documentFiles()
    {
        return $this->files()
            ->where(function ($query) {
                $query->where('mime_type', 'LIKE', 'application/pdf%')
                    ->orWhere('mime_type', 'LIKE', 'application/msword%')
                    ->orWhere('mime_type', 'LIKE', 'application/vnd.openxmlformats%');
            })
            ->get();
    }

    /**
     * Get video files.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function videoFiles()
    {
        return $this->filesByMimeType('video');
    }

    /**
     * Get total storage size in bytes.
     *
     * @return int
     */
    public function totalStorageSize(): int
    {
        return $this->files()->sum('size');
    }

    /**
     * Delete a file by ID.
     *
     * @param int $fileId
     * @return bool
     */
    public function deleteFile(int $fileId): bool
    {
        $file = $this->files()->find($fileId);

        if (!$file) {
            return false;
        }

        // TODO ETAPA 3: Delete physical file from storage
        // Storage::disk('s3')->delete($file->path);

        return $file->delete();
    }
}
