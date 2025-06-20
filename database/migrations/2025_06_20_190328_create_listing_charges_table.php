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
        Schema::create('listing_charges', function (Blueprint $table) {
            $table->id();
            $table->decimal('feature_listing_amount', 8, 2)->default(10.00); // e.g. $10
            $table->decimal('additional_listing_amount', 8, 2)->default(5.00); // e.g. $5
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_charges');
    }
};
