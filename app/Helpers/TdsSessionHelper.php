<?php

/*
|--------------------------------------------------------------------------
| TDS SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('tds_session_key')) {

    function tds_session_key(): string
    {
        return 'tds_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_tds_session')) {

    function get_tds_session(
        array $default = []
    ): array {

        return session(
            tds_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_tds_session')) {

    function save_tds_session(
        array $data
    ): void {

        session()->put(
            tds_session_key(),
            $data
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('clear_tds_session')) {

    function clear_tds_session(): void
    {
        session()->forget(
            tds_session_key()
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE PREVIEW
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_tds_preview')) {

    function prepare_tds_preview(
        array $preview
    ): array {

        $preview['data']['name'] =

            $preview['data']['name']

            ??

            'N/A';



        $preview['data']['mobile'] =

            $preview['data']['mobile']

            ??

            'N/A';



        $preview['data']['email'] =

            $preview['data']['email']

            ??

            'N/A';



        $preview['data']['remarks'] =

            $preview['data']['remarks']

            ??

            'N/A';



        $preview['data']['charge'] =

            $preview['data']['charge']

            ??

            99;

        return $preview;
    }
}