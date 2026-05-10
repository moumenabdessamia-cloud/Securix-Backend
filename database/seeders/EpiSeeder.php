<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class EpiSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'product_title' => 'Casque de sécurité',
            'product_price' => 150,
            'stock_qty'     => 50,
            'stock_min'     => 5,
            'category_id'   => 1,
            'brand_id'      => 1,
            'product_image' => 'products/casque.jpg',
        ]);

        Product::create([
            'product_title' => 'Gants de protection',
            'product_price' => 80,
            'stock_qty'     => 100,
            'stock_min'     => 10,
            'category_id'   => 1,
            'brand_id'      => 1,
            'product_image' => 'products/gants.jpg',
        ]);

        Product::create([
            'product_title' => 'Chaussures de sécurité',
            'product_price' => 250,
            'stock_qty'     => 30,
            'stock_min'     => 5,
            'category_id'   => 1,
            'brand_id'      => 1,
            'product_image' => 'products/chaussures.jpg',
        ]);

        Product::create([
            'product_title' => 'Gilet de haute visibilité',
            'product_price' => 60,
            'stock_qty'     => 200,
            'stock_min'     => 20,
            'category_id'   => 1,
            'brand_id'      => 1,
            'product_image' => 'products/gilet.jpg',
        ]);

        Product::create([
            'product_title' => 'Lunettes de protection',
            'product_price' => 45,
            'stock_qty'     => 150,
            'stock_min'     => 15,
            'category_id'   => 1,
            'brand_id'      => 1,
            'product_image' => 'products/lunettes.jpg',
        ]);
    }
}