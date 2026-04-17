<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main borrowing record — one per order that uses borrowing
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);        // full order value
            $table->decimal('paid_amount', 10, 2)->default(0);  // amount paid so far (upfront + repayments)
            $table->decimal('remaining_amount', 10, 2);    // auto-calculated, kept for easy queries
            $table->date('due_date')->nullable();           // optional expected full-payment date
            $table->string('notes')->nullable();            // e.g. "customer said will pay by Diwali"
            $table->enum('status', ['pending', 'partial', 'completed'])->default('pending');
            $table->timestamps();
        });

        // Each time the customer pays some amount towards the borrowing
        Schema::create('borrowing_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'upi', 'other'])->default('cash');
            $table->date('payment_date');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowing_payments');
        Schema::dropIfExists('borrowings');
    }
};