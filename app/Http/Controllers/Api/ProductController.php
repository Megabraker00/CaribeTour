<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="CaribeTour API",
 *     version="1.0.0",
 *     description="API documentation for CaribeTour project"
 * )
 */

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Get list of products",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter products by fields (e.g., name, category_id)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of products returned",
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
        $query = Product::query();

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
        $products = $query->paginate($request->input('limit', 15));

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/products",
     *     summary="Create a new product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Product Name"),
     *             @OA\Property(property="slug", type="string", example="product-name"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="type_id", type="integer", example=1),
     *             @OA\Property(property="status_id", type="integer", example=1),
     *             @OA\Property(property="suplier_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:products,slug',
            'category_id' => 'required|integer|exists:categories,id',
            'type_id' => 'required|integer|exists:types,id',
            'status_id' => 'required|integer|exists:statuses,id',
            'suplier_id' => 'required|integer|exists:supliers,id',
        ]);

        $product = Product::create($validatedData);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Get a product by ID",
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
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     summary="Update a product",
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
     *             @OA\Property(property="name", type="string", example="Updated Product Name"),
     *             @OA\Property(property="slug", type="string", example="updated-product-name"),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(property="type_id", type="integer", example=2),
     *             @OA\Property(property="status_id", type="integer", example=2),
     *             @OA\Property(property="suplier_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|unique:products,slug,' . $product->id,
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'type_id' => 'sometimes|required|integer|exists:types,id',
            'status_id' => 'sometimes|required|integer|exists:statuses,id',
            'suplier_id' => 'sometimes|required|integer|exists:supliers,id',
        ]);

        $product->update($validatedData);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     summary="Delete a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();

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

    /**
     * Get all itineraries for a specific product.
     *
     * @OA\Get(
     *     path="/api/v1/products/{id}/itineraries",
     *     summary="Get all itineraries for a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="product_id", type="integer"),
     *          @OA\Property(property="currency", type="string"),
     *          @OA\Property(
     *              property="data",
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Itinerary")
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function tourItineraries(Request $request, Product $product)
    {
        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year'  => 'nullable|integer|min:2000|max:2100',
        ]);

        $query = $product->itineraries()
            ->select([
                'id',
                'product_id',
                'departure_date',
                'departure_terminal_id',
                'arrival_date',
                'arrival_terminal_id',
                'price',
                'taxes'
            ]);

        if ($request->has(['month', 'year'])) {
            $query->whereYear('departure_date', $request->year)
                ->whereMonth('departure_date', $request->month);
        }

        $ret = [
            'product_id' => $product->id,
            'currency' => 'EUR',
            'data' => $query->get(),
        ];

        return response()->json($ret);
    }
}
