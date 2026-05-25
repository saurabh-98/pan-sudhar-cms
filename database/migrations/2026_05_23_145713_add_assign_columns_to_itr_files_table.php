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

        Schema::table('itr_files', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | ASSIGNED TO
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('itr_files', 'assigned_to')) {

                $table->unsignedBigInteger('assigned_to')
                      ->nullable()
                      ->after('status');

            }

            /*
            |--------------------------------------------------------------------------
            | ASSIGNED AT
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('itr_files', 'assigned_at')) {

                $table->timestamp('assigned_at')
                      ->nullable()
                      ->after('assigned_to');

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {

        Schema::table('itr_files', function (Blueprint $table) {

            if (Schema::hasColumn('itr_files', 'assigned_to')) {

                $table->dropColumn('assigned_to');

            }

            if (Schema::hasColumn('itr_files', 'assigned_at')) {

                $table->dropColumn('assigned_at');

            }

        });

    }

};