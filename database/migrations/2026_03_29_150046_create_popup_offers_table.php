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
        Schema::create('popup_offers', function (Blueprint $table) {
            $table->id();

            // 🔥 MAIN FIELDS
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            // 🔥 CONTROL
            $table->boolean('is_active')->default(1);

            // 🔥 TIME CONTROL
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_offers');
    }
};