<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('product_title');
        $table->decimal('product_price', 10, 2);
        $table->string('product_image')->nullable();
        $table->integer('stock_qty')->default(0);  // Quantité en temps réel [cite: 32, 43]
        $table->integer('stock_min')->default(10); // Seuil d'alerte critique [cite: 33]
        $table->foreignId('category_id')->constrained();
        $table->foreignId('brand_id')->constrained();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
