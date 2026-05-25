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

        Schema::create(

            'pan_correction_applications',

            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                $table->foreignId('user_id')

                    ->constrained()

                    ->cascadeOnDelete();

                /*
                |--------------------------------------------------------------------------
                | APPLICANT
                |--------------------------------------------------------------------------
                */

                $table->string('first_name');

                $table->string('middle_name')->nullable();

                $table->string('last_name');

                $table->string('old_pan_number', 10);

                $table->string('gender');

                $table->string('pan_print_name')->nullable();

                /*
                |--------------------------------------------------------------------------
                | FATHER
                |--------------------------------------------------------------------------
                */

                $table->string('father_first_name');

                $table->string('father_middle_name')->nullable();

                $table->string('father_last_name');

                /*
                |--------------------------------------------------------------------------
                | MOTHER
                |--------------------------------------------------------------------------
                */

                $table->string('mother_first_name');

                $table->string('mother_middle_name')->nullable();

                $table->string('mother_last_name');

                /*
                |--------------------------------------------------------------------------
                | CONTACT
                |--------------------------------------------------------------------------
                */

                $table->string('mobile_no', 10);

                $table->string('email');

                /*
                |--------------------------------------------------------------------------
                | ADDRESS
                |--------------------------------------------------------------------------
                */

                $table->string('house_no');

                $table->string('village');

                $table->string('post_office');

                $table->string('area');

                $table->foreignId('state');

                $table->foreignId('district');

                $table->string('pincode', 6);

                /*
                |--------------------------------------------------------------------------
                | PROOFS
                |--------------------------------------------------------------------------
                */

                $table->string('identity_proof')->nullable();

                $table->string('address_proof')->nullable();

                $table->string('dob_proof')->nullable();

                /*
                |--------------------------------------------------------------------------
                | DOB
                |--------------------------------------------------------------------------
                */

                $table->date('dob');

                /*
                |--------------------------------------------------------------------------
                | AADHAAR
                |--------------------------------------------------------------------------
                */

                $table->string('aadhaar_no', 12);

                $table->string('aadhaar_name');

                /*
                |--------------------------------------------------------------------------
                | SIGNATURE
                |--------------------------------------------------------------------------
                */

                $table->string('signature_type')->nullable();

                /*
                |--------------------------------------------------------------------------
                | FILES
                |--------------------------------------------------------------------------
                */

                $table->string('photo')->nullable();

                $table->string('signature')->nullable();

                $table->string('aadhaar_card')->nullable();

                $table->string('identity_proof_file')->nullable();

                $table->string('address_proof_file')->nullable();

                $table->string('dob_proof_file')->nullable();

                $table->string('supporting_document')->nullable();

                /*
                |--------------------------------------------------------------------------
                | ASSIGNMENT
                |--------------------------------------------------------------------------
                */

                $table->unsignedBigInteger('assigned_to')

                    ->nullable();

                $table->timestamp('assigned_at')

                    ->nullable();

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'status',

                    [

                        'Pending',

                        'Approved',

                        'Processing',

                        'Completed',

                        'Rejected'

                    ]

                )->default('Pending');

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'payment_status',

                    [

                        'Pending',

                        'Paid',

                        'Failed'

                    ]

                )->default('Pending');

                $table->decimal(

                    'amount',

                    10,

                    2

                )->default(107);

                /*
                |--------------------------------------------------------------------------
                | REMARKS
                |--------------------------------------------------------------------------
                */

                $table->text('remarks')

                    ->nullable();

                $table->timestamps();

            }

        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists(

            'pan_correction_applications'

        );

    }
};