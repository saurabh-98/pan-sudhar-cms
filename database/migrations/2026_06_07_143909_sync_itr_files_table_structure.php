<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itr_files', function (Blueprint $table) {

            if (!Schema::hasColumn('itr_files', 'mobile')) {
                $table->string('mobile', 20)->nullable()->after('name');
            }

            if (!Schema::hasColumn('itr_files', 'application_no')) {
                $table->string('application_no', 100)
                      ->nullable()
                      ->unique()
                      ->after('user_id');
            }

            if (!Schema::hasColumn('itr_files', 'admin_remarks')) {
                $table->text('admin_remarks')->nullable();
            }

            if (!Schema::hasColumn('itr_files', 'payment_status')) {
                $table->string('payment_status', 50)
                      ->default('Pending');
            }

            if (!Schema::hasColumn('itr_files', 'wallet_deducted')) {
                $table->boolean('wallet_deducted')
                      ->default(false);
            }

            if (!Schema::hasColumn('itr_files', 'wallet_deducted_at')) {
                $table->timestamp('wallet_deducted_at')
                      ->nullable();
            }

            if (!Schema::hasColumn('itr_files', 'ip_address')) {
                $table->string('ip_address', 100)
                      ->nullable();
            }

            if (!Schema::hasColumn('itr_files', 'browser')) {
                $table->text('browser')
                      ->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};