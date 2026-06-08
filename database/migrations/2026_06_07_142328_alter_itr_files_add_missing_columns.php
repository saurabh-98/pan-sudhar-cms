<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itr_files', function (Blueprint $table) {

            if (!Schema::hasColumn('itr_files', 'mobile')) {
                $table->string('mobile', 20)
                    ->nullable()
                    ->after('name');
            }

            if (!Schema::hasColumn('itr_files', 'application_no')) {
                $table->string('application_no', 100)
                    ->nullable()
                    ->unique()
                    ->after('status');
            }

            if (!Schema::hasColumn('itr_files', 'payment_status')) {
                $table->string('payment_status', 50)
                    ->default('Pending')
                    ->after('application_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('itr_files', function (Blueprint $table) {

            if (Schema::hasColumn('itr_files', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            if (Schema::hasColumn('itr_files', 'application_no')) {
                $table->dropColumn('application_no');
            }

            if (Schema::hasColumn('itr_files', 'mobile')) {
                $table->dropColumn('mobile');
            }
        });
    }
};