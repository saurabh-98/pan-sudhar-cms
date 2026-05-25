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

        Schema::create('itr_files', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('user_id')->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOCUMENTS
            |--------------------------------------------------------------------------
            */

            $table->string('aadhaar_front');

            $table->string('aadhaar_back');

            $table->string('pan_card');

            /*
            |--------------------------------------------------------------------------
            | USER DETAILS
            |--------------------------------------------------------------------------
            */

            $table->string('name');

            $table->string('email');

            $table->text('remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'pending',
                'approved',
                'rejected'

            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | ADMIN REMARKS
            |--------------------------------------------------------------------------
            */

            $table->text('admin_remarks')->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMPS
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {

        Schema::dropIfExists('itr_files');

    }

};