<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * =========================================================
     * Run Migration
     * =========================================================
     */
    public function up(): void
    {
        Schema::create('pan_find_histories', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Retailer
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Applicant
            |--------------------------------------------------------------------------
            */

            $table->string(
                'aadhaar_number',
                12
            );

            /*
            |--------------------------------------------------------------------------
            | Charges
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'amount',
                10,
                2
            )->default(0);

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'Pending',
                    'Completed',
                    'Rejected'
                ]
            )->default('Completed');

            $table->timestamps();

        });
    }

    /**
     * =========================================================
     * Rollback
     * =========================================================
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'pan_find_histories'
        );
    }
};