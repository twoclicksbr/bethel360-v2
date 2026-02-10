<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Http\Resources\V2\MinistryResource;
use Illuminate\Http\Request;

/**
 * CampusMinistryController
 *
 * Nested route: GET /campuses/{campus}/ministries
 */
class CampusMinistryController extends Controller
{
    /**
     * Get all ministries for a specific campus.
     *
     * GET /campuses/{campus}/ministries
     */
    public function index(Campus $campus, Request $request)
    {
        $query = $campus->ministries();

        // Filter by active
        if ($request->has('filter.is_active')) {
            $query->where('is_active', $request->input('filter.is_active'));
        }

        // Includes
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100);

        $ministries = $query->paginate($perPage);

        return MinistryResource::collection($ministries);
    }
}
