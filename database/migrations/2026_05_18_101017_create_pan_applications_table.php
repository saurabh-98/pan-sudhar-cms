<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | RUN MIGRATION
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        Schema::create(

            'pan_applications',

            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                $table->foreignId('user_id')

                    ->constrained()

                    ->cascadeOnDelete()

                    ->index();

                /*
                |--------------------------------------------------------------------------
                | APPLICATION
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'application_no'
                )
                ->unique()
                ->index();

                $table->string(
                    'pan_type'
                )
                ->default('New PAN');

                /*
                |--------------------------------------------------------------------------
                | PERSONAL DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'first_name'
                );

                $table->string(
                    'middle_name'
                )->nullable();

                $table->string(
                    'last_name'
                );

                $table->date(
                    'dob'
                );

                $table->enum(

                    'gender',

                    [
                        'Male',
                        'Female',
                        'Transgender'
                    ]

                );

                /*
                |--------------------------------------------------------------------------
                | FATHER DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'father_first_name'
                );

                $table->string(
                    'father_middle_name'
                )->nullable();

                $table->string(
                    'father_last_name'
                );

                /*
                |--------------------------------------------------------------------------
                | MOTHER DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'mother_first_name'
                );

                $table->string(
                    'mother_middle_name'
                )->nullable();

                $table->string(
                    'mother_last_name'
                );

                /*
                |--------------------------------------------------------------------------
                | PAN PRINT DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'pan_print_name'
                );

                /*
                |--------------------------------------------------------------------------
                | CONTACT DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'mobile_no',
                    10
                )->index();

                $table->string(
                    'email'
                )->nullable()->index();

                /*
                |--------------------------------------------------------------------------
                | ADDRESS DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'house_no'
                );

                $table->string(
                    'village'
                );

                $table->string(
                    'post_office'
                );

                $table->string(
                    'area'
                );

                $table->string(
                    'state'
                )->index();

                $table->string(
                    'district'
                )->index();

                $table->string(
                    'pincode',
                    6
                )->index();

                /*
                |--------------------------------------------------------------------------
                | PROOFS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'identity_proof'
                );

                $table->string(
                    'address_proof'
                );

                $table->string(
                    'dob_proof'
                );

                /*
                |--------------------------------------------------------------------------
                | AADHAAR DETAILS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'aadhaar_no',
                    12
                )->index();

                $table->string(
                    'aadhaar_name'
                );

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE TYPE
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'signature_type'
                );

                /*
                |--------------------------------------------------------------------------
                | DOCUMENTS
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'photo'
                );

                $table->string(
                    'signature'
                );

                $table->string(
                    'aadhaar_card'
                );

                $table->string(
                    'identity_proof_file'
                )->nullable();

                $table->string(
                    'address_proof_file'
                )->nullable();

                $table->string(
                    'dob_proof_file'
                )->nullable();

                $table->string(
                    'supporting_document'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                $table->decimal(

                    'amount',

                    10,

                    2

                )->default(107);

                $table->enum(

                    'payment_status',

                    [
                        'Pending',
                        'Paid',
                        'Failed'
                    ]

                )
                ->default('Pending')
                ->index();

                /*
                |--------------------------------------------------------------------------
                | APPLICATION STATUS
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'status',

                    [
                        'Pending',
                        'Approved',
                        'Rejected',
                        'Processing'
                    ]

                )
                ->default('Pending')
                ->index();

                /*
                |--------------------------------------------------------------------------
                | WALLET
                |--------------------------------------------------------------------------
                */

                $table->boolean(
                    'wallet_deducted'
                )->default(false);

                $table->timestamp(
                    'wallet_deducted_at'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | SECURITY
                |--------------------------------------------------------------------------
                */

                $table->ipAddress(
                    'ip_address'
                )->nullable();

                $table->longText(
                    'browser'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | ADMIN REMARK
                |--------------------------------------------------------------------------
                */

                $table->longText(
                    'admin_remark'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | TIMESTAMPS
                |--------------------------------------------------------------------------
                */

                $table->timestamps();

            }

        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::dropIfExists(
            'pan_applications'
        );
    }
};