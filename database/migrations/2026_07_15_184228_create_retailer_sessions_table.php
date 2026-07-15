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
        Schema::create('retailer_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('retailer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('login_at');

            $table->dateTime('last_activity_at');

            $table->dateTime('logout_at')->nullable();

            $table->unsignedBigInteger('duration_seconds')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_sessions');
    }
};
