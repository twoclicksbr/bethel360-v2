<?php

namespace App\Http\Controllers\V2;

use App\Models\Ministry;
use App\Http\Resources\V2\MinistryResource;
use App\Http\Requests\V2\MinistryRequest;

class MinistryController extends BaseController
{
    protected $model = Ministry::class;
    protected $resource = MinistryResource::class;
    protected $request = MinistryRequest::class;
    protected $searchableFields = ['name', 'slug', 'description'];
}
