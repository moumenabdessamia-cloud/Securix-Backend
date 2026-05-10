<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Ajoute cette liste pour autoriser l'enregistrement
    protected $fillable = [
    'product_title',
    'product_price',
    'stock_qty',
    'stock_min',
    'product_image',
    'category_id',
    'brand_id',
    'is_featured',
    'is_on_sale',
    'sale_price',
];
}
