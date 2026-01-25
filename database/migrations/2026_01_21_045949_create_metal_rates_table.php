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
        Schema::create('metal_rates', function (Blueprint $table) {
            $table->id();

            $table->enum('metal', ['gold', 'silver']);

            // Purity percentage: 99.9, 91.6, 75, 58.5, 92.5
            $table->decimal('purity_percent', 5, 2);

            $table->decimal('rate_per_gram', 10, 2);

            $table->date('rate_date');

            $table->timestamps();

            $table->unique(['metal', 'purity_percent', 'rate_date'], 'metal_purity_percent_date_unique');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metal_rates');
    }
};
