<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Services\TimelineService;
use App\Http\Resources\V2\TimelineResource;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    protected TimelineService $timelineService;

    public function __construct(TimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    /**
     * Get timeline for person.
     *
     * GET /people/{person}/timeline
     * Query: ?type=ministry_enrollment&start_date=2024-01-01&end_date=2024-12-31
     */
    public function index(Person $person, Request $request)
    {
        $request->validate([
            'type' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Filter by type
        if ($request->has('type')) {
            $timeline = $this->timelineService->buildByType($person, $request->type);
        }
        // Filter by date range
        elseif ($request->has('start_date') && $request->has('end_date')) {
            $timeline = $this->timelineService->buildByDateRange(
                $person,
                $request->start_date,
                $request->end_date
            );
        }
        // Full timeline
        else {
            $timeline = $this->timelineService->build($person);
        }

        return TimelineResource::collection($timeline);
    }
}
