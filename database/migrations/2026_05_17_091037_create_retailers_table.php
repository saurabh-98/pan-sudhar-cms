<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RUN MIGRATIONS
     */

    public function up(): void
    {
        Schema::create(

            'retailers',

            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | BASIC DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string('shop_name')
                      ->index();

                $table->string('name')
                      ->index();

                $table->string(
                    'mobile',
                    10
                )
                ->unique()
                ->index();

                $table->string('email')
                      ->unique()
                      ->index();

                /*
                |--------------------------------------------------------------------------
                | LOCATION
                |--------------------------------------------------------------------------
                */

                $table->foreignId('state_id')
                      ->constrained()
                      ->cascadeOnDelete();

                $table->foreignId('district_id')
                      ->constrained()
                      ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | LOGIN
                |--------------------------------------------------------------------------
                */

                $table->string('password');

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'status',

                    [

                        'pending',
                        'approved',
                        'rejected',
                        'blocked'

                    ]

                )

                ->default('pending')

                ->index();

                /*
                |--------------------------------------------------------------------------
                | VERIFICATION
                |--------------------------------------------------------------------------
                */

                $table->boolean('is_verified')
                      ->default(false);

                $table->timestamp(
                    'email_verified_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | SECURITY
                |--------------------------------------------------------------------------
                */

                $table->rememberToken();

                /*
                |--------------------------------------------------------------------------
                | META
                |--------------------------------------------------------------------------
                */

                $table->ipAddress(
                    'registered_ip'
                )->nullable();

                $table->timestamp(
                    'last_login_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | TIMESTAMPS
                |--------------------------------------------------------------------------
                */

                $table->timestamps();

                /*
                |--------------------------------------------------------------------------
                | COMPOSITE INDEX
                |--------------------------------------------------------------------------
                */

                $table->index([

                    'shop_name',
                    'status'

                ]);

            }

        );
    }

    /**
     * REVERSE MIGRATION
     */

    public function down(): void
    {
        Schema::dropIfExists(
            'retailers'
        );
    }
};