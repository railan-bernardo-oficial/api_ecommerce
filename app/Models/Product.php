<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'stock',
        'category_id',
        'sku',
        'barcode',
        'brand',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the specifications for the product.
     */
    public function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
