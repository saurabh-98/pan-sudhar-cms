<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | RUN MIGRATION
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        Schema::table(

            'pan_applications',

            function (Blueprint $table) {

                $table->foreignId(
                    'assigned_to'
                )
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            }

        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::table(

            'pan_applications',

            function (Blueprint $table) {

                $table->dropForeign([

                    'assigned_to'

                ]);

                $table->dropColumn(

                    'assigned_to'

                );

            }

        );
    }
};