<?php

namespace App\Http\Controllers\V2;

use App\Models\Group;
use App\Http\Resources\V2\GroupResource;
use App\Http\Requests\V2\GroupRequest;

class GroupController extends BaseController
{
    protected $model = Group::class;
    protected $resource = GroupResource::class;
    protected $request = GroupRequest::class;
    protected $searchableFields = ['name', 'slug', 'description'];
}
