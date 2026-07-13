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
        Schema::create('conversations', function (Blueprint $table) {

            $table->id();

            // Public conversation ID
            $table->uuid('conversation_id')->unique();

            // Retailer who started the conversation
            $table->foreignId('retailer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Admin handling the conversation (nullable until assigned)
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'waiting',
                'active',
                'closed',
            ])->default('waiting');

            $table->text('last_message')->nullable();

            $table->timestamp('last_message_at')->nullable();

            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index('retailer_id');
            $table->index('admin_id');
            $table->index('status');
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};