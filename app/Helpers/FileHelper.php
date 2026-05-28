<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('is_vercel')) {

    /*
    |--------------------------------------------------------------------------
    | CHECK VERCEL
    |--------------------------------------------------------------------------
    */

    function is_vercel(): bool
    {
        return (bool) env('VERCEL');
    }
}

if (!function_exists('upload_base_path')) {

    /*
    |--------------------------------------------------------------------------
    | BASE UPLOAD PATH
    |--------------------------------------------------------------------------
    */

    function upload_base_path(): string
    {
        /*
        |--------------------------------------------------------------------------
        | VERCEL
        |--------------------------------------------------------------------------
        |
        | Use temporary directory only
        | for runtime filesystem.
        |
        */

        if (is_vercel()) {

            return sys_get_temp_dir();
        }

        /*
        |--------------------------------------------------------------------------
        | LOCALHOST / CPANEL / VPS
        |--------------------------------------------------------------------------
        */

        return storage_path('app/public');
    }
}

if (!function_exists('upload_path')) {

    /*
    |--------------------------------------------------------------------------
    | FULL UPLOAD PATH
    |--------------------------------------------------------------------------
    */

    function upload_path(
        string $folder = ''
    ): string {

        $basePath = upload_base_path();

        $fullPath =

            rtrim(
                $basePath,
                DIRECTORY_SEPARATOR
            )

            . DIRECTORY_SEPARATOR .

            trim(
                $folder,
                DIRECTORY_SEPARATOR
            );

        /*
        |--------------------------------------------------------------------------
        | CREATE DIRECTORY
        |--------------------------------------------------------------------------
        */

        if (!File::exists($fullPath)) {

            File::makeDirectory(

                $fullPath,

                0777,

                true,

                true
            );
        }

        return $fullPath;
    }
}

if (!function_exists('store_uploaded_file')) {

    /*
    |--------------------------------------------------------------------------
    | STORE FILE
    |--------------------------------------------------------------------------
    */

    function store_uploaded_file(
        $file,
        string $folder
    ): ?string {

        if (!$file) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | UNIQUE FILE NAME
        |--------------------------------------------------------------------------
        */

        $filename =

            uniqid()

            . '_'

            . time()

            . '_'

            . mt_rand(1000, 9999)

            . '.'

            . strtolower(

                $file->getClientOriginalExtension()

            );

        /*
        |--------------------------------------------------------------------------
        | VERCEL
        |--------------------------------------------------------------------------
        */

        if (is_vercel()) {

            $destination =
                upload_path($folder);

            $file->move(

                $destination,

                $filename

            );

            return ltrim(

                trim($folder, '/')

                . '/'

                . $filename,

                '/'

            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOCALHOST / CPANEL / VPS
        |--------------------------------------------------------------------------
        */

        return $file->storeAs(

            trim($folder, '/'),

            $filename,

            'public'

        );
    }
}

if (!function_exists('file_url')) {

    /*
    |--------------------------------------------------------------------------
    | FILE URL
    |--------------------------------------------------------------------------
    */

    function file_url(
        ?string $path
    ): ?string {

        if (!$path) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | ALWAYS USE SECURE ROUTE
        |--------------------------------------------------------------------------
        */

        return url(

            '/temp-file/' .

            ltrim($path, '/')

        );
    }
}
if (!function_exists('file_exists_custom')) {

    /*
    |--------------------------------------------------------------------------
    | FILE EXISTS
    |--------------------------------------------------------------------------
    */

    function file_exists_custom(
        ?string $path
    ): bool {

        if (!$path) {

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | VERCEL
        |--------------------------------------------------------------------------
        */

        if (is_vercel()) {

            return File::exists(

                sys_get_temp_dir()

                . DIRECTORY_SEPARATOR

                . ltrim($path, '/')

            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOCALHOST / CPANEL / VPS
        |--------------------------------------------------------------------------
        */

        return Storage::disk('public')

            ->exists(

                ltrim($path, '/')

            );
    }
}

if (!function_exists('delete_uploaded_file')) {

    /*
    |--------------------------------------------------------------------------
    | DELETE FILE
    |--------------------------------------------------------------------------
    */

    function delete_uploaded_file(
        ?string $path
    ): bool {

        if (!$path) {

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | VERCEL
        |--------------------------------------------------------------------------
        */

        if (is_vercel()) {

            $fullPath =

                sys_get_temp_dir()

                . DIRECTORY_SEPARATOR

                . ltrim($path, '/');

            if (File::exists($fullPath)) {

                return File::delete(
                    $fullPath
                );
            }

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | LOCALHOST / CPANEL / VPS
        |--------------------------------------------------------------------------
        */

        if (

            Storage::disk('public')

                ->exists($path)

        ) {

            return Storage::disk('public')

                ->delete($path);
        }

        return false;
    }
}

if (!function_exists('ensure_upload_directories')) {

    /*
    |--------------------------------------------------------------------------
    | CREATE DEFAULT DIRECTORIES
    |--------------------------------------------------------------------------
    */

    function ensure_upload_directories(): void
    {
        $folders = [

            'pan/photo',

            'pan/signature',

            'pan/aadhaar',

            'pan/dob-proof',

            'pan/document'

        ];

        foreach ($folders as $folder) {

            upload_path($folder);
        }
    }
}