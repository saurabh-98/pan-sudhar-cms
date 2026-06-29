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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('retailer_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('amount', 12, 2);

            $table->string('upi_id');

            $table->string('merchant_name');

            $table->string('utr')->nullable();

            $table->string('screenshot')->nullable();

            $table->text('remarks')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending');

            $table->foreignId('verified_by')->nullable()->constrained('users');

            $table->timestamp('verified_at')->nullable();

            $table->text('admin_remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
