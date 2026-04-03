<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('image'); // ❌ remove old
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->text('image')->nullable(); // ✅ new
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->string('image');
        });
    }
};