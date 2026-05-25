<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNavbarFieldsToPagesTable extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {

            if (!Schema::hasColumn('pages', 'show_in_navbar')) {
                $table->boolean('show_in_navbar')->default(1)->after('status');
            }

            // Add other fields safely
            if (!Schema::hasColumn('pages', 'navbar_order')) {
                $table->integer('navbar_order')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {

            if (Schema::hasColumn('pages', 'show_in_navbar')) {
                $table->dropColumn('show_in_navbar');
            }

            if (Schema::hasColumn('pages', 'navbar_order')) {
                $table->dropColumn('navbar_order');
            }
        });
    }
}