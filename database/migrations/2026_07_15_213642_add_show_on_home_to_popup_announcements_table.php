<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('popup_announcements', function (Blueprint $table) {

            $table->boolean('show_on_home')
                  ->default(false)
                  ->after('show_on_dashboard');

        });
    }

    public function down(): void
    {
        Schema::table('popup_announcements', function (Blueprint $table) {

            $table->dropColumn('show_on_home');

        });
    }
};