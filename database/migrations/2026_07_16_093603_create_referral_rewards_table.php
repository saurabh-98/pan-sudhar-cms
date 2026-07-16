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
        Schema::create('referral_rewards', function (Blueprint $table) {

            $table->id();

            $table->foreignId('referrer_id')
                ->constrained('retailers')
                ->cascadeOnDelete();

            $table->foreignId('referred_id')
                ->constrained('retailers')
                ->cascadeOnDelete();

            $table->decimal('reward',10,2)
                ->default(100);

            $table->enum('status',[
                'Pending',
                'Approved',
                'Rejected'
            ])->default('Pending');

            $table->text('remarks')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
    }
};