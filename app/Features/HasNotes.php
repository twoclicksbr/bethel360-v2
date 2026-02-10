<?php

namespace App\Features;

use App\Models\Note;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasNotes Trait
 *
 * Adiciona relacionamento polimórfico de notas a qualquer model.
 * Usado por: Campus, Ministry, Group, Event
 */
trait HasNotes
{
    /**
     * Get all notes for the model.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Add a new note to the model.
     *
     * @param array $data
     * @return Note
     */
    public function addNote(array $data): Note
    {
        return $this->notes()->create($data);
    }

    /**
     * Get notes by person (author).
     *
     * @param int $personId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function notesByPerson(int $personId)
    {
        return $this->notes()->where('person_id', $personId)->get();
    }

    /**
     * Get recent notes (last 30 days).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentNotes()
    {
        return $this->notes()
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Search notes by content.
     *
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchNotes(string $search)
    {
        return $this->notes()
            ->where('content', 'ILIKE', "%$search%")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Delete a note by ID.
     *
     * @param int $noteId
     * @return bool
     */
    public function deleteNote(int $noteId): bool
    {
        $note = $this->notes()->find($noteId);

        if (!$note) {
            return false;
        }

        return $note->delete();
    }
}
