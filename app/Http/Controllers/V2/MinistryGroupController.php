<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Http\Resources\V2\GroupResource;
use Illuminate\Http\Request;

/**
 * MinistryGroupController
 *
 * Nested route: GET /ministries/{ministry}/groups
 */
class MinistryGroupController extends Controller
{
    /**
     * Get all groups for a specific ministry.
     *
     * GET /ministries/{ministry}/groups
     */
    public function index(Ministry $ministry, Request $request)
    {
        $query = $ministry->groups();

        // Filter by active
        if ($request->has('filter.is_active')) {
            $query->where('is_active', $request->input('filter.is_active'));
        }

        // Filter by confidential
        if ($request->has('filter.is_confidential')) {
            $query->where('is_confidential', $request->input('filter.is_confidential'));
        }

        // Includes
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100);

        $groups = $query->paginate($perPage);

        return GroupResource::collection($groups);
    }
}
