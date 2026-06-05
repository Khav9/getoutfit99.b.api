<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'description',
        'category_id',
        'price_usd',
        'sale_price_usd',
        'stock_qty',
        'is_new_arrival',
    ];

    protected $casts = [
        'price_usd'      => 'decimal:2',
        'sale_price_usd' => 'decimal:2',
        'stock_qty'      => 'integer',
        'is_new_arrival' => 'boolean',
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

     public function productImages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(product_images::class);
    }
}
