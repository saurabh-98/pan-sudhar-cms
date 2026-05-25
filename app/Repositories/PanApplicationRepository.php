<?php

namespace App\Repositories;

use App\Models\PanApplication;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PanApplicationRepository
{
    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): PanApplication {

        return PanApplication::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(
        int $userId
    ): LengthAwarePaginator {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->latest()

            ->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER
    |--------------------------------------------------------------------------
    */

    public function findByUser(
        int $id,
        int $userId
    ): PanApplication {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        PanApplication $application,
        array $data
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | REMOVE FILE OBJECTS
        |--------------------------------------------------------------------------
        */

        unset(

            $data['photo'],
            $data['signature'],
            $data['aadhaar_card'],
            $data['identity_proof_file'],
            $data['address_proof_file'],
            $data['dob_proof_file'],
            $data['supporting_document']

        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        return $application->update([

            /*
            |--------------------------------------------------------------------------
            | PERSONAL
            |--------------------------------------------------------------------------
            */

            'first_name' =>
                $data['first_name'] ?? $application->first_name,

            'middle_name' =>
                $data['middle_name'] ?? $application->middle_name,

            'last_name' =>
                $data['last_name'] ?? $application->last_name,

            'gender' =>
                $data['gender'] ?? $application->gender,

            /*
            |--------------------------------------------------------------------------
            | FATHER
            |--------------------------------------------------------------------------
            */

            'father_first_name' =>

                $data['father_first_name']

                ?? $application->father_first_name,

            'father_middle_name' =>

                $data['father_middle_name']

                ?? $application->father_middle_name,

            'father_last_name' =>

                $data['father_last_name']

                ?? $application->father_last_name,

            /*
            |--------------------------------------------------------------------------
            | MOTHER
            |--------------------------------------------------------------------------
            */

            'mother_first_name' =>

                $data['mother_first_name']

                ?? $application->mother_first_name,

            'mother_middle_name' =>

                $data['mother_middle_name']

                ?? $application->mother_middle_name,

            'mother_last_name' =>

                $data['mother_last_name']

                ?? $application->mother_last_name,

            /*
            |--------------------------------------------------------------------------
            | PAN
            |--------------------------------------------------------------------------
            */

            'pan_print_name' =>

                $data['pan_print_name']

                ?? $application->pan_print_name,

            /*
            |--------------------------------------------------------------------------
            | CONTACT
            |--------------------------------------------------------------------------
            */

            'mobile_no' =>
                $data['mobile_no']
                ?? $application->mobile_no,

            'email' =>
                $data['email']
                ?? $application->email,

            /*
            |--------------------------------------------------------------------------
            | ADDRESS
            |--------------------------------------------------------------------------
            */

            'house_no' =>
                $data['house_no']
                ?? $application->house_no,

            'village' =>
                $data['village']
                ?? $application->village,

            'post_office' =>
                $data['post_office']
                ?? $application->post_office,

            'area' =>
                $data['area']
                ?? $application->area,

            'state' =>
                $data['state']
                ?? $application->state,

            'district' =>
                $data['district']
                ?? $application->district,

            'pincode' =>
                $data['pincode']
                ?? $application->pincode,

            /*
            |--------------------------------------------------------------------------
            | PROOFS
            |--------------------------------------------------------------------------
            */

            'identity_proof' =>

                $data['identity_proof']

                ?? $application->identity_proof,

            'address_proof' =>

                $data['address_proof']

                ?? $application->address_proof,

            'dob_proof' =>

                $data['dob_proof']

                ?? $application->dob_proof,

            /*
            |--------------------------------------------------------------------------
            | DOB
            |--------------------------------------------------------------------------
            */

            'dob' =>
                $data['dob']
                ?? $application->dob,

            'confirm_dob' =>

                $data['confirm_dob']

                ?? $application->confirm_dob,

            /*
            |--------------------------------------------------------------------------
            | AADHAAR
            |--------------------------------------------------------------------------
            */

            'aadhaar_no' =>
                $data['aadhaar_no']
                ?? $application->aadhaar_no,

            'aadhaar_name' =>
                $data['aadhaar_name']
                ?? $application->aadhaar_name,

            /*
            |--------------------------------------------------------------------------
            | SIGNATURE
            |--------------------------------------------------------------------------
            */

            'signature_type' =>

                $data['signature_type']

                ?? $application->signature_type,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        PanApplication $application
    ): bool {

        return $application->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | CHANGE STATUS
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        PanApplication $application,
        string $status
    ): bool {

        return $application->update([

            'status' =>
                $status

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    public function updatePaymentStatus(
        PanApplication $application,
        string $status
    ): bool {

        return $application->update([

            'payment_status' =>
                $status

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function totalApplications(
        int $userId
    ): int {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function pendingApplications(
        int $userId
    ): int {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Pending'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function approvedApplications(
        int $userId
    ): int {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Approved'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | REJECTED APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function rejectedApplications(
        int $userId
    ): int {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->where(
                'status',
                'Rejected'
            )

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT APPLICATIONS
    |--------------------------------------------------------------------------
    */

    public function recentApplications(
        int $userId,
        int $limit = 5
    ) {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->latest()

            ->take($limit)

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH APPLICATION
    |--------------------------------------------------------------------------
    */

    public function search(
        int $userId,
        ?string $keyword
    ): LengthAwarePaginator {

        return PanApplication::query()

            ->where(
                'user_id',
                $userId
            )

            ->when(

                $keyword,

                function ($query) use ($keyword) {

                    $query->where(

                        function ($q) use ($keyword) {

                            $q->where(
                                'application_no',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'first_name',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'last_name',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'mobile_no',
                                'LIKE',
                                "%{$keyword}%"
                            )

                            ->orWhere(
                                'aadhaar_no',
                                'LIKE',
                                "%{$keyword}%"
                            );

                        }

                    );

                }

            )

            ->latest()

            ->paginate(10);
    }
}