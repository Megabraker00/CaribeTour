<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Get list of categories",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter categories by fields (e.g., name)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of categories returned",
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
        $query = Category::query();

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
        $categories = $query->paginate($request->input('limit', 15));

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Category Name"),
     *             @OA\Property(property="slug", type="string", example="category-name"),
     *             @OA\Property(property="status_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'status_id' => 'required|integer|exists:statuses,id',
        ]);

        $category = Category::create($validatedData);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     summary="Get a category by ID",
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
    public function show(Category $category)
    {
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     summary="Update a category",
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
     *             @OA\Property(property="name", type="string", example="Updated Category Name"),
     *             @OA\Property(property="slug", type="string", example="updated-category-name"),
     *             @OA\Property(property="status_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:categories,slug,' . $category->id,
            'status_id' => 'sometimes|required|integer|exists:statuses,id',
        ]);

        $category->update($validatedData);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     )
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();

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
