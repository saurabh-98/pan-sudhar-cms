<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::create('service_documents', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SERVICE TYPE
            |--------------------------------------------------------------------------
            |
            | pan
            | itr
            | gst
            | wallet
            | recharge
            |
            */

            $table->string('service_type');

            /*
            |--------------------------------------------------------------------------
            | SERVICE ID
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('service_id');

            /*
            |--------------------------------------------------------------------------
            | UPLOADED USER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FILE
            |--------------------------------------------------------------------------
            */

            $table->string('file_path');

            /*
            |--------------------------------------------------------------------------
            | REMARKS
            |--------------------------------------------------------------------------
            */

            $table->text('remarks')

                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOCUMENT TYPE
            |--------------------------------------------------------------------------
            |
            | receipt
            | invoice
            | proof
            | verification
            |
            */

            $table->string('document_type')

                ->default('receipt');

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('service_documents');
    }
};