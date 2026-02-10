<?php

namespace App\Http\Controllers\V2;

use App\Models\Campus;
use App\Http\Resources\V2\CampusResource;
use App\Http\Requests\V2\CampusRequest;

class CampusController extends BaseController
{
    protected $model = Campus::class;
    protected $resource = CampusResource::class;
    protected $request = CampusRequest::class;
    protected $searchableFields = ['name', 'slug'];
}
