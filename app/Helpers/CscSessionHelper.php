<?php

/*
|--------------------------------------------------------------------------
| CSC SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('csc_session_key')) {

    function csc_session_key(): string
    {
        return 'csc_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET CSC SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_csc_session')) {

    function get_csc_session(
        array $default = []
    ): array {

        return session(
            csc_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE CSC SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_csc_session')) {

    function save_csc_session(
        array $data
    ): void {

        session()->put(

            csc_session_key(),

            $data

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR CSC SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('clear_csc_session')) {

    function clear_csc_session(): void
    {
        session()->forget(

            csc_session_key()

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE CSC PREVIEW
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_csc_preview')) {

    function prepare_csc_preview(
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

        $preview['data']['email'] =

            $preview['data']['email']
            ?? '';

        $preview['data']['remarks'] =

            $preview['data']['remarks']
            ?? '';

        $preview['data']['application_type'] =

            $preview['data']['application_type']
            ?? '';

        $preview['data']['address'] =

            $preview['data']['address']
            ?? '';

        return $preview;
    }
}