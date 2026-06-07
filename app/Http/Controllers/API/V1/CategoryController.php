<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Category::all();

        return response()->json($query);
    }

    /**
     * Display the specified category with its products.
     */
    public function show(string $slug)
    {
        $perPage = request()->query('per_page', 15);

        // Find category by slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // Query products that belong to this category
        $query = Product::with([
            'category',
            'productImages',
            'colors',
            'brand',
        ])->where('category_id', $category->id);

        // Search product name
        if ($q = request()->query('q')) {
            $query->where('name', 'like', "%{$q}%");
        }

        return response()->json([
            'category' => $category,
            'products' => $query->paginate($perPage),
        ]);
    }
}
