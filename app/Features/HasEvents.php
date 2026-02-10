<?php

namespace App\Features;

use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * HasEvents Trait
 *
 * Agenda de eventos (não existe agenda sem ministério).
 * Eventos sempre pertencem a Ministry ou Group.
 * Usado por: Ministry, Group
 */
trait HasEvents
{
    /**
     * Get all events for the model.
     */
    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    /**
     * Create a new event.
     *
     * @param array $data
     * @return Event
     */
    public function createEvent(array $data): Event
    {
        return $this->events()->create($data);
    }

    /**
     * Get upcoming events.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function upcomingEvents()
    {
        return $this->events()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * Get past events.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function pastEvents()
    {
        return $this->events()
            ->where('end_date', '<', now())
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get events by date range.
     *
     * @param \Carbon\Carbon $startDate
     * @param \Carbon\Carbon $endDate
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function eventsByDateRange($startDate, $endDate)
    {
        return $this->events()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * Get next event.
     *
     * @return Event|null
     */
    public function nextEvent(): ?Event
    {
        return $this->events()
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->first();
    }

    /**
     * Get events count.
     *
     * @return int
     */
    public function eventsCount(): int
    {
        return $this->events()->count();
    }
}
