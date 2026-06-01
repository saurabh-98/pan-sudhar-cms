<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'pan_correction_applications',
            function (Blueprint $table) {

               
                /*
                |--------------------------------------------------------------------------
                | ADMIN REMARK
                |--------------------------------------------------------------------------
                */

                $table->longText('admin_remark')
                    ->nullable()
                    ->after('remarks');

                /*
                |--------------------------------------------------------------------------
                | WALLET
                |--------------------------------------------------------------------------
                */

                $table->boolean('wallet_deducted')
                    ->default(false)
                    ->after('amount');

                $table->timestamp('wallet_deducted_at')
                    ->nullable()
                    ->after('wallet_deducted');

                /*
                |--------------------------------------------------------------------------
                | TRACKING
                |--------------------------------------------------------------------------
                */

                $table->ipAddress('ip_address')
                    ->nullable()
                    ->after('wallet_deducted_at');

                $table->text('browser')
                    ->nullable()
                    ->after('ip_address');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'pan_correction_applications',
            function (Blueprint $table) {

              
                $table->dropColumn([


                    'admin_remark',


                    'wallet_deducted',

                    'wallet_deducted_at',

                    'ip_address',

                    'browser'

                ]);
            }
        );
    }
};