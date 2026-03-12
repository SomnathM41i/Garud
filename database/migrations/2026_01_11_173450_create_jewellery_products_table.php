<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('jewellery_products', function (Blueprint $table) {
            $table->id();

          
            $table->string('product_name', 150);



            $table->string('metal_type', 50);

            /* ===== WEIGHTS ===== */
            $table->decimal('gross_weight', 10, 3);
            $table->decimal('stone_weight', 10, 3)->default(0);
            $table->decimal('net_weight', 10, 3);

            /* ===== PURITY ===== */
            $table->decimal('purity_percent', 5, 2);
            $table->decimal('fine_gold_weight', 10, 3);

            /* ===== COST ===== */
            $table->decimal('cost_price', 12, 2);
            $table->decimal('handling_cost', 12, 2)->default(0);

            /* ===== MAKING (SIMPLE) ===== */
            $table->decimal('making_charge', 12, 2)->default(0);

            $table->decimal('buying_purity_percent', 5, 2);
            $table->decimal('buying_price', 12, 2);

            $table->integer('stock_quantity')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jewellery_products');
    }
};
