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
        Schema::create('profit_partner_transactions', function (Blueprint $table) {

            $table->id();

            // Partner
            $table->foreignId('partner_id')
                ->constrained('profit_partners')
                ->cascadeOnDelete();

            // Service Information
            $table->string('service_type');          // new_pan, aadhaar, itr...
            $table->unsignedBigInteger('service_id');
            $table->string('reference_no')->nullable();

            // Revenue
            $table->decimal('service_amount', 12, 2)->default(0);

            // Commission deducted
            $table->decimal('executive_commission', 12, 2)->default(0);
            $table->decimal('distributor_commission', 12, 2)->default(0);

            // Net profit after commission
            $table->decimal('net_profit', 12, 2)->default(0);

            // Partner Share
            $table->decimal('profit_percentage', 5, 2);
            $table->decimal('profit_amount', 12, 2);

            // Optional remarks
            $table->text('remarks')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_partner_transactions');
    }
};