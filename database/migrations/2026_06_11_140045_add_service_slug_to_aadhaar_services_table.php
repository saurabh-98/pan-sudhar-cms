<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            if (! Schema::hasColumn('aadhaar_services', 'service_slug')) {

                $table->string('service_slug')
                    ->nullable()
                    ->after('service_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('aadhaar_services', function ($table) {

            $table->dropColumn('service_slug');

        });
    }
};
