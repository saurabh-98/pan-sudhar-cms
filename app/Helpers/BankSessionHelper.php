<?php

/*
|--------------------------------------------------------------------------
| BANK SESSION KEY
|--------------------------------------------------------------------------
*/

if (! function_exists('bank_session_key')) {

    function bank_session_key(): string
    {
        return 'bank_account_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET BANK SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('get_bank_session')) {

    function get_bank_session(
        array $default = []
    ): array {

        return session(
            bank_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE BANK SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('save_bank_session')) {

    function save_bank_session(
        array $data
    ): void {

        session()->put(

            bank_session_key(),

            $data

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| CLEAR BANK SESSION
|--------------------------------------------------------------------------
*/

if (! function_exists('clear_bank_session')) {

    function clear_bank_session(): void
    {
        session()->forget(

            bank_session_key()

        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREPARE BANK PREVIEW
|--------------------------------------------------------------------------
*/

if (! function_exists('prepare_bank_preview')) {

    function prepare_bank_preview(
        array $preview
    ): array {

        $preview['data']['customer_name'] =

            $preview['data']['customer_name']
            ?? '';

        $preview['data']['mobile'] =

            $preview['data']['mobile']
            ?? '';

        $preview['data']['aadhaar_number'] =

            $preview['data']['aadhaar_number']
            ?? '';

        $preview['data']['pan_number'] =

            $preview['data']['pan_number']
            ?? '';

        $preview['data']['email'] =

            $preview['data']['email']
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

        $preview['data']['remarks'] =

            $preview['data']['remarks']
            ?? '';

        return $preview;
    }
}