<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 2, 'cat_title' => 'Gants', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'cat_title' => 'Chaussures', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'cat_title' => 'Gilets', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'cat_title' => 'Lunettes', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}