<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2); // 5.00 or 10.00
            $table->string('payment_type'); // e.g. 'second_listing', 'featured_listing'
            $table->string('payment_status')->default('pending'); // e.g. 'pending', 'completed', 'failed'
            $table->string('payment_gateway')->nullable(); // e.g. 'stripe'
            $table->string('transaction_id')->nullable(); // Stripe transaction reference
            $table->foreignId('listing_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
