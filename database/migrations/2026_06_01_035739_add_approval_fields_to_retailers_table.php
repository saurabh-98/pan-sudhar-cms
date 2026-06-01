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
        Schema::table('retailers', function (Blueprint $table) {
            
            $table->unsignedBigInteger('user_id')->nullable();

            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamp('approved_at')->nullable();

            $table->unsignedBigInteger('rejected_by')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->text('rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retailers', function (Blueprint $table) {
            //
        });
    }
};
