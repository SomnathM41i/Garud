<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SALES USER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('sales_user_id')
                ->constrained('users')
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | PRODUCT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('product_id')
                ->constrained('jewellery_products')
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | QUANTITY
            |--------------------------------------------------------------------------
            */

            $table->integer('quantity')->default(1);

            /*
            |--------------------------------------------------------------------------
            | GOLD SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $table->decimal('gross_weight', 10, 3)->nullable();
            $table->decimal('net_weight', 10, 3)->nullable();
            $table->decimal('fine_gold_weight', 10, 3)->nullable();
            $table->decimal('purity_percent', 5, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | BUYING SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $table->decimal('buying_purity_percent', 5, 2)->nullable();
            $table->decimal('buying_gold_weight', 10, 3)->nullable();

            /*
            |--------------------------------------------------------------------------
            | RATE SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $table->decimal('gold_rate', 10, 2)->nullable();
            $table->decimal('gold_value', 12, 2)->nullable();
            $table->decimal('making_charge', 12, 2)->nullable();

            /*
            |--------------------------------------------------------------------------
            | SELLING
            |--------------------------------------------------------------------------
            */

            $table->decimal('selling_price', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | PROFIT
            |--------------------------------------------------------------------------
            */

            $table->decimal('profit_gold', 10, 4)->nullable();
            $table->decimal('total_profit', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};