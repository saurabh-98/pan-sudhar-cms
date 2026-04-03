<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTablesStructure extends Migration
{
    public function up()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->integer('capacity')->default(2);   
             
        });
    }

    public function down()
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
}