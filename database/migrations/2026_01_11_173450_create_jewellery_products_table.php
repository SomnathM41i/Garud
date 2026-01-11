<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jewellery_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code', 50)->unique();
            $table->string('product_name', 150);

            $table->foreignId('category_id')
                ->constrained('jewellery_categories')
                ->onDelete('cascade'); // optional: delete products if category deleted

            $table->string('metal_type', 50); // Gold, Silver
            $table->string('purity', 20)->nullable(); // 22K, 18K, 925
            $table->decimal('weight', 10, 2)->nullable(); // grams
            $table->decimal('making_charges', 10, 2)->nullable();
            $table->decimal('price', 12, 2);

            $table->integer('stock_quantity')->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewellery_products');
    }
};
