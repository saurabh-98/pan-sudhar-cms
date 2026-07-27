<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_ledgers', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Service
            |--------------------------------------------------------------------------
            */

            $table->string('service_type');

            $table->unsignedBigInteger('service_id');

            $table->string('reference_no')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */

            $table->foreignId('retailer_id')
                ->constrained('users');

            $table->foreignId('executive_id')
                ->constrained('users');

            $table->foreignId('distributor_id')
                ->nullable()
                ->constrained('users');

            /*
            |--------------------------------------------------------------------------
            | Financial
            |--------------------------------------------------------------------------
            */

            $table->decimal('service_amount',12,2);

            $table->decimal('executive_commission',12,2)->default(0);

            $table->decimal('distributor_commission',12,2)->default(0);

            $table->decimal('net_profit',12,2)->default(0);

            $table->decimal('partner_profit',12,2)->default(0);

            $table->decimal('company_profit',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Meta
            |--------------------------------------------------------------------------
            */

            $table->string('charge_code')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index([
                'service_type',
                'service_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_ledgers');
    }
};