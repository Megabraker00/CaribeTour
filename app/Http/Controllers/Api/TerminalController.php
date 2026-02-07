<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use Illuminate\Http\Request;

class TerminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/v1/terminals",
     *     summary="Get list of terminals",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter terminals by fields (e.g., name)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit the number of terminals returned",
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
        $query = Terminal::query();

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
        $terminals = $query->paginate($request->input('limit', 15));

        return response()->json($terminals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/v1/terminals",
     *     summary="Create a new terminal",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Terminal Name"),
     *             @OA\Property(property="address", type="string", example="123 Main St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Terminal created successfully"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $terminal = Terminal::create($validatedData);

        return response()->json($terminal, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/v1/terminals/{id}",
     *     summary="Get a terminal by ID",
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
    public function show(Terminal $terminal)
    {
        return response()->json($terminal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/v1/terminals/{id}",
     *     summary="Update a terminal",
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
     *             @OA\Property(property="name", type="string", example="Updated Terminal Name"),
     *             @OA\Property(property="address", type="string", example="456 Elm St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Terminal updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Terminal $terminal)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ]);

        $terminal->update($validatedData);

        return response()->json($terminal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/terminals/{id}",
     *     summary="Delete a terminal",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Terminal deleted successfully"
     *     )
     * )
     */
    public function destroy(Terminal $terminal)
    {
        $terminal->delete();

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
