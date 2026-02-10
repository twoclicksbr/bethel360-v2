<?php

namespace App\Http\Controllers\V2;

use App\Models\Event;
use App\Http\Resources\V2\EventResource;
use App\Http\Requests\V2\EventRequest;

class EventController extends BaseController
{
    protected $model = Event::class;
    protected $resource = EventResource::class;
    protected $request = EventRequest::class;
    protected $searchableFields = ['name', 'description'];
}
