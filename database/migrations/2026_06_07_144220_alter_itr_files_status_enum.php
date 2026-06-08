<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE itr_files
            MODIFY status
            ENUM(
                'Pending',
                'Processing',
                'Approved',
                'Rejected'
            )
            DEFAULT 'Pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE itr_files
            MODIFY status
            ENUM(
                'Pending',
                'Approved',
                'Rejected'
            )
            DEFAULT 'Pending'
        ");
    }
};