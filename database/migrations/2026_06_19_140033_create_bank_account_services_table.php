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
        Schema::create('bank_account_services', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('application_no')
                ->unique();

            $table->string('service_name');

            $table->string('service_slug');

            $table->json('form_data')
                ->nullable();

            $table->json('documents')
                ->nullable();

            $table->decimal('amount', 10, 2)
                ->default(0);

            $table->string('payment_status')
                ->default('Pending');

            $table->string('status')
                ->default('Pending');

            $table->boolean('wallet_deducted')
                ->default(false);

            $table->timestamp('wallet_deducted_at')
                ->nullable();

            $table->string('ip_address')
                ->nullable();

            $table->longText('browser')
                ->nullable();

            $table->text('admin_remark')
                ->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('service_slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_account_services');
    }
};