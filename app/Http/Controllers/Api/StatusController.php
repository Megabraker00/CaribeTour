<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/statuses",
     *     summary="Get list of statuses",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter statuses by fields (e.g., name)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of statuses returned",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Status::query();

        // Apply filters
        if ($request->has('filter')) {
            $filter = $request->input('filter');

            // Check if the filter is a JSON object or a plain string
            if ($this->isJson($filter)) {
                $filters = json_decode($filter, true);
                foreach ($filters as $field => $value) {
                    $query->where($field, 'LIKE', "%$value%");
                }
            } else {
                $query->where('name', 'LIKE', "%$filter%");
            }
        }

        // Apply limit
        if ($request->has('limit')) {
            $query->limit($request->input('limit'));
        }

        // Paginate results
        $statuses = $query->paginate($request->input('limit', 15));

        return response()->json($statuses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/statuses",
     *     summary="Create a new status",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Status Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Status created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $status = Status::create($validatedData);

        return response()->json($status, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/statuses/{id}",
     *     summary="Get a status by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function show(Status $status)
    {
        return response()->json($status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/statuses/{id}",
     *     summary="Update a status",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Updated Status Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $status->update($validatedData);

        return response()->json($status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/statuses/{id}",
     *     summary="Delete a status",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Status deleted successfully"
     *     )
     * )
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return response()->json(null, 204);
    }

    /**
     * Helper function to check if a string is a valid JSON.
     */
    private function isJson($string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
