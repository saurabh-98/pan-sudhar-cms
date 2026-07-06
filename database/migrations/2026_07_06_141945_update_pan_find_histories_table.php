<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            $table->string('application_no')
                ->unique()
                ->after('id');

            $table->string('payment_status')
                ->default('unpaid')
                ->after('amount');

            $table->boolean('wallet_deducted')
                ->default(false)
                ->after('payment_status');

            $table->timestamp('wallet_deducted_at')
                ->nullable()
                ->after('wallet_deducted');

            // Change enum to include Approved
            $table->enum('status', [
                'Pending',
                'Approved',
                'Completed',
                'Rejected'
            ])->default('Pending')->change();

            $table->foreignId('assigned_to')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('assigned_at')
                ->nullable()
                ->after('assigned_to');

            $table->text('remarks')
                ->nullable()
                ->after('assigned_at');

            $table->text('admin_remark')
                ->nullable()
                ->after('remarks');

            $table->json('extra_fields')
                ->nullable()
                ->after('admin_remark');

            $table->json('documents')
                ->nullable()
                ->after('extra_fields');

        });
    }

    public function down(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            $table->dropForeign(['assigned_to']);

            $table->dropColumn([
                'application_no',
                'payment_status',
                'wallet_deducted',
                'wallet_deducted_at',
                'assigned_to',
                'assigned_at',
                'remarks',
                'admin_remark',
                'extra_fields',
                'documents'
            ]);

        });
    }
};