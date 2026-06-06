<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $query = Category::with(['products'])->where('slug', $slug);

        if ($q = request()->query('q')) {
            $query->where('name', 'like', "%{$q}%");
        }

        return response()->json($query->paginate($perPage));
    }
}
