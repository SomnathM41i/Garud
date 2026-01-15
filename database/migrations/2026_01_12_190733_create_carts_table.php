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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Sales user (who is creating the order)
            $table->foreignId('sales_user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Product
            $table->foreignId('product_id')
                ->constrained('jewellery_products')
                ->onDelete('cascade');

            // Quantity selected
            $table->integer('quantity')->default(1);

            // Snapshot of selling price at cart time
            $table->decimal('selling_price', 12, 2);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
