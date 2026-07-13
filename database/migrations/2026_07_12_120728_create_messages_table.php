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
        Schema::create('messages', function (Blueprint $table) {

            $table->id();

            $table->foreignId('conversation_id')
                ->constrained('conversations')
                ->cascadeOnDelete();

            // admin / retailer
            $table->enum('sender_type', [
                'admin',
                'retailer',
            ]);

            // User ID of sender
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->longText('message')->nullable();

            $table->string('attachment')->nullable();

            $table->string('attachment_name')->nullable();

            $table->string('attachment_type')->nullable();

            $table->boolean('is_read')->default(false);

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index('conversation_id');
            $table->index('sender_type');
            $table->index('sender_id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};