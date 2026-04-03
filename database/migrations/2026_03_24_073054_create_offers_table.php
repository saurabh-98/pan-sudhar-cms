<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['percent', 'fixed', 'freebie']);
            $table->decimal('value', 10, 2)->nullable();
            $table->foreignId('menu_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->string('code')->unique();          
            $table->decimal('min_order', 10, 2)->nullable(); 
            $table->decimal('max_discount', 10, 2)->nullable(); 
            $table->timestamp('expires_at')->nullable(); 
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};