<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            $table->string('full_name')->nullable()->after('aadhaar_number');

            $table->string('pan_number', 10)->nullable()->after('full_name');

            $table->date('dob')->nullable()->after('pan_number');

            $table->enum('gender', [
                'Male',
                'Female',
                'Other'
            ])->nullable()->after('dob');

        });
    }

    public function down(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            $table->dropColumn([
                'full_name',
                'pan_number',
                'dob',
                'gender'
            ]);

        });
    }
};