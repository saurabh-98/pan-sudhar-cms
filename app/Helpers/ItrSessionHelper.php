<?php

/*
|--------------------------------------------------------------------------
| ITR SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('itr_session_key')) {

    function itr_session_key(): string
    {
        return 'itr_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_itr_session')) {

    function get_itr_session(
        array $default = []
    ): array {

        return session(
            itr_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_itr_session')) {

    function save_itr_session(
        array $data
    ): void {

        session()->put(
            itr_session_key(),
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

if (!function_exists('clear_itr_session')) {

    function clear_itr_session(): void
    {
        session()->forget(
            itr_session_key()
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE PREVIEW
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_itr_preview')) {

    function prepare_itr_preview(
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