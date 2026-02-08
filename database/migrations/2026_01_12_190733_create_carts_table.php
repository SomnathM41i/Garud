<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

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

            /* =====================
             * GOLD SNAPSHOT
             * ===================== */
            $table->decimal('gross_weight', 10, 3)->nullable();      // grams
            $table->decimal('net_weight', 10, 3)->nullable();        // grams
            $table->decimal('fine_gold_weight', 10, 3)->nullable();  // grams
            $table->decimal('purity_percent', 5, 2)->nullable();     // e.g. 91.60%

            $table->decimal('gold_rate', 10, 2)->nullable();         // rate per gram
            $table->decimal('gold_value', 12, 2)->nullable();        // total gold value
            $table->decimal('making_charge', 12, 2)->nullable();

            /* =====================
             * SELLING
             * ===================== */

            // Snapshot selling price (per item)
            $table->decimal('selling_price', 12, 2);

            // ADD THIS (missing in your migration)
            $table->decimal('total_profit', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
