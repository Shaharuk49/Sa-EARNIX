<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_category_id',
        'name',
        'slug',
        'base_price',
        'vat_percent',
        'final_price',
        'stock',
        'description',
        'is_active',
    ];

    // Relationship: Product belongs to one Category
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    // Relationship: Product has many Media (Images, Videos)
    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    // Relationship: Product has many Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}