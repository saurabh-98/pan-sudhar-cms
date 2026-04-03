<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->decimal('total', 10, 2)->default(0);     
            $table->decimal('discount', 10, 2)->default(0); 
            $table->decimal('final_total', 10, 2);    
            $table->string('offer_code')->nullable();
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'out_for_delivery',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->enum('payment_method', ['COD', 'online'])->default('COD');
            $table->string('payment_status')->default('pending');
            $table->text('address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};