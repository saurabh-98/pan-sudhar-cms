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
        Schema::table('pan_correction_applications', function (Blueprint $table) {
            $table->string('pan_type')->nullable()->after('application_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pan_correction_applications', function (Blueprint $table) {
            $table->dropColumn('pan_type');
        });
    }
};