<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            if (!Schema::hasColumn('pan_find_histories', 'father_name')) {

                $table->string('father_name')
                    ->nullable()
                    ->after('full_name');

            }

        });
    }

    public function down(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            if (Schema::hasColumn('pan_find_histories', 'father_name')) {

                $table->dropColumn('father_name');

            }

        });
    }
};