<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Add New Columns
        |--------------------------------------------------------------------------
        */

        Schema::table('pan_find_histories', function (Blueprint $table) {

            if (!Schema::hasColumn('pan_find_histories', 'application_no')) {

                $table->string('application_no')
                    ->after('id');

            }

            if (!Schema::hasColumn('pan_find_histories', 'payment_status')) {

                $table->string('payment_status')
                    ->default('unpaid')
                    ->after('amount');

            }

            if (!Schema::hasColumn('pan_find_histories', 'wallet_deducted')) {

                $table->boolean('wallet_deducted')
                    ->default(false)
                    ->after('payment_status');

            }

            if (!Schema::hasColumn('pan_find_histories', 'wallet_deducted_at')) {

                $table->timestamp('wallet_deducted_at')
                    ->nullable()
                    ->after('wallet_deducted');

            }

            if (!Schema::hasColumn('pan_find_histories', 'assigned_to')) {

                $table->foreignId('assigned_to')
                    ->nullable()
                    ->after('status')
                    ->constrained('users')
                    ->nullOnDelete();

            }

            if (!Schema::hasColumn('pan_find_histories', 'assigned_at')) {

                $table->timestamp('assigned_at')
                    ->nullable()
                    ->after('assigned_to');

            }

            if (!Schema::hasColumn('pan_find_histories', 'remarks')) {

                $table->text('remarks')
                    ->nullable()
                    ->after('assigned_at');

            }

            if (!Schema::hasColumn('pan_find_histories', 'admin_remark')) {

                $table->text('admin_remark')
                    ->nullable()
                    ->after('remarks');

            }

            if (!Schema::hasColumn('pan_find_histories', 'extra_fields')) {

                $table->json('extra_fields')
                    ->nullable()
                    ->after('admin_remark');

            }

            if (!Schema::hasColumn('pan_find_histories', 'documents')) {

                $table->json('documents')
                    ->nullable()
                    ->after('extra_fields');

            }

        });

        /*
        |--------------------------------------------------------------------------
        | Change Status Enum
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('pan_find_histories', 'status')) {

            Schema::table('pan_find_histories', function (Blueprint $table) {

                $table->enum('status', [
                    'Pending',
                    'Approved',
                    'Completed',
                    'Rejected',
                ])->default('Pending')->change();

            });

        }
    }

    public function down(): void
    {
        Schema::table('pan_find_histories', function (Blueprint $table) {

            if (Schema::hasColumn('pan_find_histories', 'assigned_to')) {

                $table->dropForeign(['assigned_to']);

            }

            $columns = [];

            foreach ([
                'application_no',
                'payment_status',
                'wallet_deducted',
                'wallet_deducted_at',
                'assigned_to',
                'assigned_at',
                'remarks',
                'admin_remark',
                'extra_fields',
                'documents',
            ] as $column) {

                if (Schema::hasColumn('pan_find_histories', $column)) {

                    $columns[] = $column;

                }

            }

            if (!empty($columns)) {

                $table->dropColumn($columns);

            }

        });
    }
};