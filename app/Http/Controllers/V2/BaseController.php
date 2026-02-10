<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * BaseController
 *
 * CRUD genérico completo com:
 * - Paginação (?page=N&per_page=N)
 * - Filtros (?filter[campo]=valor)
 * - Busca (?search=texto)
 * - Ordenação (?sort=campo ou ?sort=-campo)
 * - Includes (?include=relacao1,relacao2)
 * - Soft delete e restore
 *
 * Controller filho só define: $model, $resource, $request, $searchableFields
 */
class BaseController extends Controller
{
    /**
     * Model class (e.g., Ministry::class)
     */
    protected $model;

    /**
     * Resource class (e.g., MinistryResource::class)
     */
    protected $resource;

    /**
     * Request class for validation (e.g., MinistryRequest::class)
     */
    protected $request;

    /**
     * Searchable fields for ?search= parameter
     */
    protected $searchableFields = [];

    /**
     * List all records with pagination, filters, search, sorting, includes.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = $this->model::query();

        // Filters: ?filter[name]=value
        if ($request->has('filter')) {
            foreach ($request->filter as $field => $value) {
                // Handle null values
                if ($value === 'null' || $value === null) {
                    $query->whereNull($field);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // Search: ?search=term
        if ($request->has('search') && !empty($this->searchableFields)) {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                foreach ($this->searchableFields as $field) {
                    $q->orWhere($field, 'ILIKE', "%{$searchTerm}%");
                }
            });
        }

        // Sort: ?sort=field or ?sort=-field (descending)
        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortDirection = 'asc';

            if (str_starts_with($sortField, '-')) {
                $sortField = substr($sortField, 1);
                $sortDirection = 'desc';
            }

            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sort by created_at desc
            $query->orderBy('created_at', 'desc');
        }

        // Includes: ?include=relation1,relation2
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        // Pagination: ?page=N&per_page=N (default 15)
        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100); // Max 100 per page

        $results = $query->paginate($perPage);

        return $this->resource::collection($results);
    }

    /**
     * Show a single record.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResource
     */
    public function show($id, Request $request)
    {
        $query = $this->model::query();

        // Includes: ?include=relation1,relation2
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $query->with($includes);
        }

        $record = $query->findOrFail($id);

        return new $this->resource($record);
    }

    /**
     * Store a new record.
     *
     * @param Request $request
     * @return JsonResource
     */
    public function store(Request $request)
    {
        // Validate using request class
        $validated = $this->validateRequest($request);

        // Create record
        $record = $this->model::create($validated);

        // Load includes if specified
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $record->load($includes);
        }

        return (new $this->resource($record))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResource
     */
    public function update($id, Request $request)
    {
        // Validate using request class
        $validated = $this->validateRequest($request);

        // Find and update record
        $record = $this->model::findOrFail($id);
        $record->update($validated);

        // Load includes if specified
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $record->load($includes);
        }

        return new $this->resource($record);
    }

    /**
     * Soft delete a record.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $record = $this->model::findOrFail($id);
        $record->delete();

        return response()->json(null, 204);
    }

    /**
     * Restore a soft-deleted record.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResource
     */
    public function restore($id, Request $request)
    {
        $record = $this->model::withTrashed()->findOrFail($id);
        $record->restore();

        // Load includes if specified
        if ($request->has('include')) {
            $includes = explode(',', $request->include);
            $record->load($includes);
        }

        return new $this->resource($record);
    }

    /**
     * Validate request using request class.
     *
     * @param Request $request
     * @return array
     */
    protected function validateRequest(Request $request): array
    {
        if ($this->request) {
            $formRequest = app($this->request);
            return $formRequest->validator($request->all())->validate();
        }

        return $request->all();
    }
}
