<?php

namespace App\Http\Controllers\V2;

use App\Models\Person;
use App\Http\Resources\V2\PersonResource;
use App\Http\Requests\V2\PersonRequest;

class PersonController extends BaseController
{
    protected $model = Person::class;
    protected $resource = PersonResource::class;
    protected $request = PersonRequest::class;
    protected $searchableFields = ['first_name', 'last_name', 'qr_code'];
}
