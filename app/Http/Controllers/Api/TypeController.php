<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/types",
     *     summary="Get list of types",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter types by fields (e.g., name)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of types returned",
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
        $query = Type::query();

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
        $types = $query->paginate($request->input('limit', 15));

        return response()->json($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/types",
     *     summary="Create a new type",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Type Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Type created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = Type::create($validatedData);

        return response()->json($type, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/types/{id}",
     *     summary="Get a type by ID",
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
    public function show(Type $type)
    {
        return response()->json($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/types/{id}",
     *     summary="Update a type",
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
     *             @OA\Property(property="name", type="string", example="Updated Type Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Type updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Type $type)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $type->update($validatedData);

        return response()->json($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/types/{id}",
     *     summary="Delete a type",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Type deleted successfully"
     *     )
     * )
     */
    public function destroy(Type $type)
    {
        $type->delete();

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
