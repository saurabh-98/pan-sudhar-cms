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
            | Application Identifier
            |--------------------------------------------------------------------------
            */

            $table->string('application_no')
                ->unique();

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

            $table->string('payment_status')
                ->default('unpaid');

            $table->boolean('wallet_deducted')
                ->default(false);

            $table->timestamp('wallet_deducted_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'Pending',
                    'Approved',
                    'Completed',
                    'Rejected'
                ]
            )->default('Pending');

            /*
            |--------------------------------------------------------------------------
            | Assignment Workflow
            |--------------------------------------------------------------------------
            */

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')
                ->nullable();

            $table->text('remarks')
                ->nullable();

            $table->text('admin_remark')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Dynamic Application Fields (customer_name, mobile, etc.)
            |--------------------------------------------------------------------------
            */

            $table->json('extra_fields')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Originally Submitted Documents (name => path/url)
            |--------------------------------------------------------------------------
            */

            $table->json('documents')
                ->nullable();

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