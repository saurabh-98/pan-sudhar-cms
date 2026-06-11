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
        Schema::create('aadhaar_services', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | USER INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | APPLICATION DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('application_no')
                ->unique();

            $table->string('service_name');

            /*
            |--------------------------------------------------------------------------
            | CUSTOMER DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('customer_name');

            $table->string('mobile', 20);

            $table->string('aadhaar_number', 20)
                ->nullable();

            $table->string('father_name')
                ->nullable();

            $table->string('email')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOCUMENTS
            |--------------------------------------------------------------------------
            */

            $table->string('aadhaar_front')
                ->nullable();

            $table->string('aadhaar_back')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')
                ->nullable();

            $table->text('admin_remark')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $table->decimal(
                'amount',
                10,
                2
            )->default(0);

            $table->enum(
                'payment_status',
                [
                    'Pending',
                    'Paid',
                    'Failed'
                ]
            )->default('Pending');

            /*
            |--------------------------------------------------------------------------
            | APPLICATION STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum(
                'status',
                [
                    'Pending',
                    'Processing',
                    'Approved',
                    'Rejected',
                    'Completed'
                ]
            )->default('Pending');

            /*
            |--------------------------------------------------------------------------
            | WALLET
            |--------------------------------------------------------------------------
            */

            $table->boolean(
                'wallet_deducted'
            )->default(false);

            $table->timestamp(
                'wallet_deducted_at'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | SECURITY
            |--------------------------------------------------------------------------
            */

            $table->ipAddress(
                'ip_address'
            )->nullable();

            $table->longText(
                'browser'
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | INDEXES
            |--------------------------------------------------------------------------
            */

            $table->index('application_no');

            $table->index('service_name');

            $table->index('mobile');

            $table->index('status');

            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aadhaar_services');
    }
};