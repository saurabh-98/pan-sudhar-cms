<?php

use App\Models\State;
use App\Models\District;


/*
|--------------------------------------------------------------------------
| PAN SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('pan_session_key')) {

    function pan_session_key(): string
    {
        return 'pan_application_' . auth()->id();
    }
}


/*
|--------------------------------------------------------------------------
| GET PAN SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_pan_session')) {

    function get_pan_session(
        array $default = []
    ): array {

        return session(
            pan_session_key(),
            $default
        );
    }
}


/*
|--------------------------------------------------------------------------
| SAVE PAN SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_pan_session')) {

    function save_pan_session(
        array $data
    ): void {

        session()->put(
            pan_session_key(),
            $data
        );

        session()->save();
    }
}


/*
|--------------------------------------------------------------------------
| DELETE PAN SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('clear_pan_session')) {

    function clear_pan_session(): void
    {
        session()->forget(
            pan_session_key()
        );

        session()->save();
    }
}


/*
|--------------------------------------------------------------------------
| ADD LOCATION NAME
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_pan_preview')) {

    function prepare_pan_preview(
        array $preview
    ): array {


        $preview['data']['state_name'] =

            $preview['data']['state_name']

            ??

            State::where(
                'id',
                $preview['data']['state'] ?? null
            )
            ->value('name')

            ??

            'N/A';



        $preview['data']['district_name'] =

            $preview['data']['district_name']

            ??

            District::where(
                'id',
                $preview['data']['district'] ?? null
            )
            ->value('name')

            ??

            'N/A';


        return $preview;
    }
}