<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\PromotionProduct;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->query('per_page', 15);
        
        $promotions = Promotion::with([
                'promotionProducts.product',
                'promotionProducts.product.category',
                'promotionProducts.product.productImages',
                'promotionProducts.product.colors',
                'promotionProducts.product.brand',
            ])
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->paginate($perPage);
            
            $data = $promotions->through(function ($promotion) {
                $productPerPromotion = 6;
                return [
                    'id' => $promotion->id,
                    'name' => $promotion->name,
                    'slug' => $promotion->slug,
                    'products' => $promotion->promotionProducts->take($productPerPromotion)->map(function ($item) {
                        $product = $item->product;

                        return [
                            ...$product->toArray(),
                            'price_usd' => $product->price_usd,
                            'sale_price_usd' => $item->discount_price,
                            'discount' => $item->discount_percent,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Promotions loaded successfully',
            'data' => $promotions,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $slug)
    {
        $perPage = $request->query('per_page', 15);

        $promotion = Promotion::where('slug', $slug)
            ->where('is_active', true)
            ->where('ends_at', '>=', now())
            ->firstOrFail();

        $promotionProducts = PromotionProduct::with([
                'product.category',
                'product.productImages',
                'product.colors',
                'product.brand',
            ])
            ->where('promotion_id', $promotion->id)
            ->paginate($perPage);

        $promotionProducts->getCollection()->transform(function ($item) {
            $product = $item->product;

            return [
                ...$product->toArray(),
                'price_usd' => $product->price_usd,
                'sale_price_usd' => $item->discount_price,
                'discount' => $item->discount_percent,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Promotion loaded successfully',
            'data' => [
                'id' => $promotion->id,
                'name' => $promotion->name,
                'slug' => $promotion->slug,
                'products' => $promotionProducts,
            ],
        ]);
    }
}
