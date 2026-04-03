<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {

            // Add description only if not exists
            if (!Schema::hasColumn('menus', 'description')) {
                $table->text('description')->nullable()->after('category_id');
            }

            // Add specifications only if not exists
            if (!Schema::hasColumn('menus', 'specifications')) {
                $table->text('specifications')->nullable()->after('description');
            }

        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {

            // Drop only if exists (safe rollback)
            if (Schema::hasColumn('menus', 'specifications')) {
                $table->dropColumn('specifications');
            }

            if (Schema::hasColumn('menus', 'description')) {
                $table->dropColumn('description');
            }

        });
    }
};