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
        Schema::create('tds_files', function (Blueprint $table) {

            $table->id();

            // User Details
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('application_no')->unique();

            $table->string('name');
            $table->string('mobile', 20);
            $table->string('email')->nullable();

            // Remarks
            $table->text('remarks')->nullable();
            $table->text('admin_remarks')->nullable();

            // Assignment
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')->nullable();

            // Documents
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();
            $table->string('pan_card')->nullable();

            // Charges
            $table->decimal('charge', 10, 2)->default(0);

            // Payment
            $table->enum('payment_status', [
                'Pending',
                'Paid',
                'Failed',
                'Refunded'
            ])->default('Pending');

            // Application Status
            $table->enum('status', [
                'Pending',
                'Processing',
                'Approved',
                'Rejected'
            ])->default('Pending');

            // Wallet
            $table->boolean('wallet_deducted')->default(false);
            $table->timestamp('wallet_deducted_at')->nullable();

            // Visitor Information
            $table->string('ip_address', 45)->nullable();
            $table->text('browser')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('application_no');
            $table->index('mobile');
            $table->index('email');
            $table->index('status');
            $table->index('payment_status');
            $table->index('assigned_to');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tds_files');
    }
};