<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | DYNAMIC SERVICE SUPPORT
            |--------------------------------------------------------------------------
            */

            $table->string('service_slug')
                ->nullable()
                ->after('service_name');

            $table->json('form_data')
                ->nullable()
                ->after('service_slug');

            $table->json('documents')
                ->nullable()
                ->after('form_data');

        });
    }

    public function down(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            $table->dropColumn([
                'service_slug',
                'form_data',
                'documents'
            ]);

        });
    }
};
