<?php

use App\Models\State;
use App\Models\District;

/*
|--------------------------------------------------------------------------
| PAN CORRECTION SESSION KEY
|--------------------------------------------------------------------------
*/

if (!function_exists('pan_correction_session_key')) {

    function pan_correction_session_key(): string
    {
        return 'pan_correction_application_' . auth()->id();
    }
}

/*
|--------------------------------------------------------------------------
| GET SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('get_pan_correction_session')) {

    function get_pan_correction_session(
        array $default = []
    ): array {

        return session(
            pan_correction_session_key(),
            $default
        );
    }
}

/*
|--------------------------------------------------------------------------
| SAVE SESSION
|--------------------------------------------------------------------------
*/

if (!function_exists('save_pan_correction_session')) {

    function save_pan_correction_session(
        array $data
    ): void {

        session()->put(
            pan_correction_session_key(),
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

if (!function_exists('clear_pan_correction_session')) {

    function clear_pan_correction_session(): void
    {
        session()->forget(
            pan_correction_session_key()
        );

        session()->save();
    }
}

/*
|--------------------------------------------------------------------------
| PREVIEW HELPER
|--------------------------------------------------------------------------
*/

if (!function_exists('prepare_pan_correction_preview')) {

    function prepare_pan_correction_preview(
        array $preview
    ): array {

        $preview['data']['state_name'] =

            $preview['data']['state_name']

            ??

            State::where(
                'id',
                $preview['data']['state'] ?? null
            )->value('name')

            ??

            'N/A';


        $preview['data']['district_name'] =

            $preview['data']['district_name']

            ??

            District::where(
                'id',
                $preview['data']['district'] ?? null
            )->value('name')

            ??

            'N/A';

        return $preview;
    }
}