<?php

/*
|--------------------------------------------------------------------------
| OTHER SERVICE SESSION KEY
|--------------------------------------------------------------------------
*/

if (! function_exists('other_service_session_key')) {

    function other_service_session_key(): string
    {
        return 'other_service_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET OTHER SERVICE SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('get_other_service_session')) {

    function get_other_service_session(
        array $default = []
    ): array {

        return session(
            other_service_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE OTHER SERVICE SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('save_other_service_session')) {

    function save_other_service_session(
        array $data
    ): void {

        session()->put(

            other_service_session_key(),

            $data

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR OTHER SERVICE SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('clear_other_service_session')) {

    function clear_other_service_session(): void
    {
        session()->forget(

            other_service_session_key()

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| COMPATIBILITY HELPERS
|--------------------------------------------------------------------------
*/

if (! function_exists('get_other_services_session')) {

    function get_other_services_session(
        array $default = []
    ): array {

        return get_other_service_session(
            $default
        );
    }
}

if (! function_exists('save_other_services_session')) {

    function save_other_services_session(
        array $data
    ): void {

        save_other_service_session(
            $data
        );
    }
}

if (! function_exists('clear_other_services_session')) {

    function clear_other_services_session(): void
    {
        clear_other_service_session();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE OTHER SERVICE PREVIEW
|--------------------------------------------------------------------------
*/

if (! function_exists('prepare_other_service_preview')) {

    function prepare_other_service_preview(
        array $preview
    ): array {

        $preview['data']['customer_name'] =
            $preview['data']['customer_name']
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

        $preview['data']['address'] =
            $preview['data']['address']
            ?? '';

        $preview['data']['service_name'] =
            $preview['data']['service_name']
            ?? '';

        $preview['data']['service_slug'] =
            $preview['data']['service_slug']
            ?? '';

        $preview['data']['other_service_charge'] =
            $preview['data']['other_service_charge']
            ?? 0;

        $preview['data']['remarks'] =
            $preview['data']['remarks']
            ?? '';

        return $preview;
    }
}