<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // ✅ ADD NEW COLUMNS
            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->default(0)->after('discount');
            }

            if (!Schema::hasColumn('orders', 'delivery')) {
                $table->decimal('delivery', 10, 2)->default(0)->after('tax');
            }

        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // ✅ DROP COLUMNS SAFELY
            if (Schema::hasColumn('orders', 'tax')) {
                $table->dropColumn('tax');
            }

            if (Schema::hasColumn('orders', 'delivery')) {
                $table->dropColumn('delivery');
            }

        });
    }
};