<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('jewellery_products', function (Blueprint $table) {
            $table->id();

            $table->string('product_code', 50)->unique();
            $table->string('product_name', 150);

            $table->foreignId('category_id')
                ->constrained('jewellery_categories')
                ->onDelete('cascade');

            $table->string('metal_type', 50);          // Gold, Silver
            $table->string('purity', 20)->nullable();  // 22K, 18K
            $table->decimal('weight', 10, 2)->nullable(); // grams

            /* ===== COST STRUCTURE ===== */
            $table->decimal('cost_price', 12, 2);          // What YOU pay
            $table->decimal('handling_cost', 12, 2)->default(0); // Making / labour

            /* ===== SELLING ===== */
            $table->decimal('selling_price', 12, 2);       // Default selling price

            $table->integer('stock_quantity')->default(0);
            $table->text('description')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jewellery_products');
    }
};
