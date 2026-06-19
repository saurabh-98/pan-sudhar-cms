<?php

/*
|--------------------------------------------------------------------------
| VOTER ID SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('voter_id_session_key')) {

    function voter_id_session_key(): string
    {
        return 'voter_id_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET VOTER ID SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_voter_id_session')) {

    function get_voter_id_session(
        array $default = []
    ): array {

        return session(
            voter_id_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE VOTER ID SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_voter_id_session')) {

    function save_voter_id_session(
        array $data
    ): void {

        session()->put(
            voter_id_session_key(),
            $data
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR VOTER ID SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('clear_voter_id_session')) {

    function clear_voter_id_session(): void
    {
        session()->forget(
            voter_id_session_key()
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE VOTER ID PREVIEW
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_voter_id_preview')) {

    function prepare_voter_id_preview(
        array $preview
    ): array {

        $preview['data']['service_name'] =
            $preview['data']['service_name']
            ?? '';

        $preview['data']['applicant_name'] =
            $preview['data']['applicant_name']
            ?? '';

        $preview['data']['father_name'] =
            $preview['data']['father_name']
            ?? '';

        $preview['data']['mother_name'] =
            $preview['data']['mother_name']
            ?? '';

        $preview['data']['mobile'] =
            $preview['data']['mobile']
            ?? '';

        $preview['data']['email'] =
            $preview['data']['email']
            ?? '';

        $preview['data']['aadhaar_number'] =
            $preview['data']['aadhaar_number']
            ?? '';

        $preview['data']['epic_number'] =
            $preview['data']['epic_number']
            ?? '';

        $preview['data']['dob'] =
            $preview['data']['dob']
            ?? '';

        $preview['data']['gender'] =
            $preview['data']['gender']
            ?? '';

        $preview['data']['address'] =
            $preview['data']['address']
            ?? '';

        $preview['data']['district'] =
            $preview['data']['district']
            ?? '';

        $preview['data']['state'] =
            $preview['data']['state']
            ?? '';

        $preview['data']['pincode'] =
            $preview['data']['pincode']
            ?? '';

        $preview['data']['new_mobile'] =
            $preview['data']['new_mobile']
            ?? '';

        $preview['data']['new_address'] =
            $preview['data']['new_address']
            ?? '';

        $preview['data']['current_dob'] =
            $preview['data']['current_dob']
            ?? '';

        $preview['data']['new_dob'] =
            $preview['data']['new_dob']
            ?? '';

        $preview['data']['correction_details'] =
            $preview['data']['correction_details']
            ?? '';

        $preview['data']['remarks'] =
            $preview['data']['remarks']
            ?? '';

        return $preview;
    }
}