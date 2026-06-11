<?php

/*
|--------------------------------------------------------------------------
| AADHAAR SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('aadhaar_session_key')) {

    function aadhaar_session_key(): string
    {
        return 'aadhaar_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET AADHAAR SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_aadhaar_session')) {

    function get_aadhaar_session(
        array $default = []
    ): array {

        return session(
            aadhaar_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE AADHAAR SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_aadhaar_session')) {

    function save_aadhaar_session(
        array $data
    ): void {

        session()->put(

            aadhaar_session_key(),

            $data

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR AADHAAR SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('clear_aadhaar_session')) {

    function clear_aadhaar_session(): void
    {
        session()->forget(

            aadhaar_session_key()

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE AADHAAR PREVIEW
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_aadhaar_preview')) {

    function prepare_aadhaar_preview(
        array $preview
    ): array {

        $preview['data']['customer_name'] =

            $preview['data']['customer_name']
            ?? '';

        $preview['data']['mobile'] =

            $preview['data']['mobile']
            ?? '';

        $preview['data']['service_name'] =

            $preview['data']['service_name']
            ?? '';

        $preview['data']['aadhaar_number'] =

            $preview['data']['aadhaar_number']
            ?? '';

        $preview['data']['father_name'] =

            $preview['data']['father_name']
            ?? '';

        $preview['data']['email'] =

            $preview['data']['email']
            ?? '';

        $preview['data']['remarks'] =

            $preview['data']['remarks']
            ?? '';

        return $preview;
    }
}
