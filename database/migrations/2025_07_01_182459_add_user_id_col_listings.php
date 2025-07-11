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
        Schema::table('listings', function (Blueprint $table) {
            //
            // $table->index('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

              // Step 1: Add the user_id column
        $table->unsignedBigInteger('user_id')->nullable()->after('id');

        // Step 2: Add the foreign key constraint
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
