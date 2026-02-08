<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('metal_rates', function (Blueprint $table) {
            $table->id();

            // gold / silver
            $table->enum('metal', ['gold', 'silver']);

            // Example: 99.90, 91.60, 75.00, 58.50, 92.50
            $table->decimal('purity_percent', 5, 2);

            // NEW FIELD (added as per model)
            $table->decimal('buying_purity_percent', 5, 2)->nullable();

            // Example: 6200.50
            $table->decimal('rate_per_gram', 10, 2);

            $table->date('rate_date');

            $table->timestamps();

            // Prevent duplicate rate entry for same metal + purity + date
            $table->unique(
                ['metal', 'purity_percent', 'rate_date'],
                'metal_purity_percent_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metal_rates');
    }
};
