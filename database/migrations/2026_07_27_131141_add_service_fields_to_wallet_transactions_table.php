<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {

            $table->string('service_type')
                ->nullable()
                ->after('transaction_type');

            $table->unsignedBigInteger('service_id')
                ->nullable()
                ->after('service_type');

            $table->string('reference_no')
                ->nullable()
                ->after('service_id');

        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {

            $table->dropColumn([
                'service_type',
                'service_id',
                'reference_no',
            ]);

        });
    }
};