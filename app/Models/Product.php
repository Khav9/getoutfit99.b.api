<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'category_id',
        'price_usd',
        'stock_qty',
        'is_new_arrival',
    ];

    protected $casts = [
        'price_usd'      => 'decimal:2',
        'stock_qty'      => 'integer',
        'is_new_arrival' => 'boolean',
    ];

    protected $appends = ['sale_price_usd', 'discount'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(product_images::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Color::class);
    }

    public function promotionProducts()
    {
        return $this->hasMany(PromotionProduct::class);
    }
    
    //method
    public function getSalePriceUsdAttribute()
    {
        $promotionProduct = $this->promotionProducts
            ->first(fn ($pp) =>
                $pp->promotion &&
                $pp->promotion->is_active &&
                $pp->promotion->ends_at >= now()
            );

        if (!$promotionProduct) {
            return null;
        }

        $discount = $promotionProduct->discount_percent; // fix here too

        return round(
            $this->price_usd - ($this->price_usd * $discount / 100),
            2
        );
    }

    public function getDiscountAttribute()
    {
        $promotionProduct = $this->promotionProducts
            ->first(fn ($pp) =>
                $pp->promotion &&
                $pp->promotion->is_active &&
                $pp->promotion->ends_at >= now()
            );

        return $promotionProduct?->discount_percent;
    }
}
