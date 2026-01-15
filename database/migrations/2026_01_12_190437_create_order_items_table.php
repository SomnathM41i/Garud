<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('product_id')
                ->constrained('jewellery_products')
                ->onDelete('cascade');

            $table->integer('quantity');

            /* ===== SNAPSHOT AT SELL TIME ===== */
            $table->decimal('selling_price', 12, 2);   // charged to customer
            $table->decimal('cost_price', 12, 2);      // product cost
            $table->decimal('handling_cost', 12, 2)->default(0); // making/labour

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
