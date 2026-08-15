<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pan_without_document', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | APPLICATION
            |--------------------------------------------------------------------------
            */

            $table->string('application_no')
                ->nullable()
                ->after('assigned_to');

            $table->string('pan_type')
                ->nullable()
                ->after('application_no');


            /*
            |--------------------------------------------------------------------------
            | WALLET
            |--------------------------------------------------------------------------
            */

            $table->boolean('wallet_deducted')
                ->default(false)
                ->after('payment_status');

            $table->timestamp('wallet_deducted_at')
                ->nullable()
                ->after('wallet_deducted');


            /*
            |--------------------------------------------------------------------------
            | REQUEST / TRACKING
            |--------------------------------------------------------------------------
            */

            $table->string('ip_address')
                ->nullable()
                ->after('wallet_deducted_at');

            $table->text('browser')
                ->nullable()
                ->after('ip_address');


            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            $table->text('admin_remark')
                ->nullable()
                ->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pan_without_document', function (Blueprint $table) {

            $table->dropColumn([
                'application_no',
                'pan_type',
                'wallet_deducted',
                'wallet_deducted_at',
                'ip_address',
                'browser',
                'admin_remark',
            ]);
        });
    }
};