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
        Schema::table('referral_rewards', function (Blueprint $table) {

            $table->timestamp('release_at')
                ->nullable()
                ->after('approved_at');

            $table->boolean('wallet_credited')
                ->default(false)
                ->after('release_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_rewards', function (Blueprint $table) {

            $table->dropColumn([
                'release_at',
                'wallet_credited'
            ]);

        });
    }
};