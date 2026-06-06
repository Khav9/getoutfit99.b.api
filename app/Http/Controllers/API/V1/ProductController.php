<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page', 15);

        $query = Product::with([
            'category',
            'productImages',
            'colors',
            'brand',
        ]);

        if ($categoryId = request()->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($q = request()->query('q')) {
            $query->where('name', 'like', "%{$q}%");
        }

        return response()->json($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|alpha_dash|unique:products,slug',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'price_usd' => 'required|numeric|min:0',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'is_new_arrival' => 'sometimes|boolean',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load(['category', 'productImages']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = Product::with(['category', 'productImages', 'colors', 'brand'])->where('slug', $slug)->firstOrFail();

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'slug' => [
                'required',
                'string',
                'alpha_dash',
                Rule::unique('products', 'slug')->ignore($product->id),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'price_usd' => 'required|numeric|min:0',
            'sale_price_usd' => 'nullable|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'is_new_arrival' => 'sometimes|boolean',
        ]);

        $product->update($validated);

        return response()->json($product->load(['category', 'productImages']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->delete();

        return response()->json(null, 204);
    }
}
