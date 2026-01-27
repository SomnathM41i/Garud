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

            /* ===== GOLD SNAPSHOT ===== */
            $table->decimal('gross_weight', 10, 3);
            $table->decimal('net_weight', 10, 3);
            $table->decimal('fine_gold_weight', 10, 3);
            $table->decimal('purity_percent', 5, 2);

            $table->decimal('gold_rate', 10, 2);
            $table->decimal('gold_value', 12, 2);
            $table->decimal('making_charge', 12, 2);

            /* ===== FINANCIAL SNAPSHOT ===== */
            $table->decimal('selling_price', 12, 2);
            $table->decimal('cost_price', 12, 2);
            $table->decimal('handling_cost', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
