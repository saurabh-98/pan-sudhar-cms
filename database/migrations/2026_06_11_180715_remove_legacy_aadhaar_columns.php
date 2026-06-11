<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            $table->dropColumn([
                'customer_name',
                'mobile',
                'aadhaar_number',
                'father_name',
                'email',
                'aadhaar_front',
                'aadhaar_back',
                'remarks',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('aadhaar_services', function (Blueprint $table) {

            $table->string('customer_name')->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('aadhaar_number',20)->nullable();
            $table->string('father_name')->nullable();
            $table->string('email')->nullable();
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();
            $table->text('remarks')->nullable();

        });
    }
};