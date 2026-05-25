<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('itr_files', function (Blueprint $table) {

            $table->decimal(

                'charge',

                10,

                2

            )->default(0)->after('remarks');

        });

    }

    public function down(): void
    {

        Schema::table('itr_files', function (Blueprint $table) {

            $table->dropColumn('charge');

        });

    }
};