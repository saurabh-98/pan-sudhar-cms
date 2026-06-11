<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            if (!Schema::hasColumn('aadhaar_services', 'form_data')) {
                $table->json('form_data')
                    ->nullable()
                    ->after('service_slug');
            }

            if (!Schema::hasColumn('aadhaar_services', 'documents')) {
                $table->json('documents')
                    ->nullable()
                    ->after('form_data');
            }

        });
    }

    public function down(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            if (Schema::hasColumn('aadhaar_services', 'form_data')) {
                $table->dropColumn('form_data');
            }

            if (Schema::hasColumn('aadhaar_services', 'documents')) {
                $table->dropColumn('documents');
            }

        });
    }
};