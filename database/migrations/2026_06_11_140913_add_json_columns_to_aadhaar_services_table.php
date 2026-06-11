<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            $table->string('customer_name')
                ->nullable()
                ->change();

            $table->string('mobile', 20)
                ->nullable()
                ->change();

            $table->string('aadhaar_number', 20)
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            $table->string('customer_name')
                ->nullable(false)
                ->change();

            $table->string('mobile', 20)
                ->nullable(false)
                ->change();

            $table->string('aadhaar_number', 20)
                ->nullable(false)
                ->change();
        });
    }
};