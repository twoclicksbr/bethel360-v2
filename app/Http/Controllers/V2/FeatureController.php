<?php

namespace App\Http\Controllers\V2;

use App\Models\Feature;
use Illuminate\Http\Request;

/**
 * FeatureController
 *
 * Read-only - features são seedados, não criados via API.
 */
class FeatureController extends BaseController
{
    protected $model = Feature::class;
    protected $searchableFields = ['name', 'slug'];

    /**
     * Override to disable store.
     */
    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Features cannot be created via API',
        ], 403);
    }

    /**
     * Override to disable update.
     */
    public function update($id, Request $request)
    {
        return response()->json([
            'message' => 'Features cannot be updated via API',
        ], 403);
    }

    /**
     * Override to disable destroy.
     */
    public function destroy($id)
    {
        return response()->json([
            'message' => 'Features cannot be deleted via API',
        ], 403);
    }
}
