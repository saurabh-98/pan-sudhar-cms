<?php

use Illuminate\Support\Facades\File;

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

        /*
        |--------------------------------------------------------------------------
        | VALIDATE FILE
        |--------------------------------------------------------------------------
        */

        if (

            !$file

            ||

            !$file->isValid()

        ) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | ALLOWED EXTENSIONS
        |--------------------------------------------------------------------------
        */

        $allowed = [

            'jpg',

            'jpeg',

            'png',

            'webp',

            'pdf'

        ];

        $extension = strtolower(

            $file->getClientOriginalExtension()

        );

        if (

            !in_array(
                $extension,
                $allowed
            )

        ) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | UNIQUE PUBLIC ID
        |--------------------------------------------------------------------------
        */

        $publicId =

            uniqid()

            . '_'

            . time()

            . '_'

            . mt_rand(1000, 9999);

        /*
        |--------------------------------------------------------------------------
        | UPLOAD TO CLOUDINARY
        |--------------------------------------------------------------------------
        */

        $uploadedFile = cloudinary()

            ->upload(

                $file->getRealPath(),

                [

                    'folder' => $folder,

                    'public_id' => $publicId,

                    'resource_type' => 'auto'

                ]

            );

        /*
        |--------------------------------------------------------------------------
        | RETURN SECURE URL
        |--------------------------------------------------------------------------
        */

        return $uploadedFile
            ->getSecurePath();
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

        return $path;
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

        return !empty($path);
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

        /*
        |--------------------------------------------------------------------------
        | EMPTY PATH
        |--------------------------------------------------------------------------
        */

        if (!$path) {

            return false;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | EXTRACT PUBLIC ID
            |--------------------------------------------------------------------------
            */

            $parts = parse_url($path);

            if (

                !isset($parts['path'])

            ) {

                return false;
            }

            $pathInfo = pathinfo(
                $parts['path']
            );

            $publicId =

                str_replace(

                    '/',

                    '',

                    dirname(
                        $pathInfo['dirname']
                    )

                )

                . '/'

                . $pathInfo['filename'];

            /*
            |--------------------------------------------------------------------------
            | DELETE FROM CLOUDINARY
            |--------------------------------------------------------------------------
            */

            cloudinary()->destroy(

                $publicId,

                [

                    'resource_type' => 'auto'

                ]

            );

            return true;

        } catch (\Throwable $e) {

            return false;
        }
    }
}

if (!function_exists('ensure_upload_directories')) {

    /*
    |--------------------------------------------------------------------------
    | CREATE DEFAULT DIRECTORIES
    |--------------------------------------------------------------------------
    |--------------------------------------------------------------------------
    |
    | Not required for Cloudinary.
    |
    */

    function ensure_upload_directories(): void
    {
        return;
    }
}