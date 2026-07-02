<?php

use Illuminate\Support\Facades\File;
use Cloudinary\Cloudinary;


/*
|--------------------------------------------------------------------------
| CHECK VERCEL / CLOUDINARY MODE
|--------------------------------------------------------------------------
*/

if (!function_exists('is_vercel')) {

    function is_vercel(): bool
    {
        return
            app()->environment('production')
            ||
            env('VERCEL') === '1'
            ||
            env('FILESYSTEM_DISK') === 'cloudinary';
    }
}


/*
|--------------------------------------------------------------------------
| NORMALIZE PATH
|--------------------------------------------------------------------------
*/

if (!function_exists('normalize_file_path')) {

    function normalize_file_path(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return $path;
        }

        return 'uploads/'.$path;
    }
}


/*
|--------------------------------------------------------------------------
| STORE UPLOADED FILE
|--------------------------------------------------------------------------
*/

if (!function_exists('store_uploaded_file')) {

    function store_uploaded_file($file, string $folder): ?string
{
    if (!$file || !$file->isValid()) {
        return null;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    $extension = strtolower($file->getClientOriginalExtension());

    if (!in_array($extension, $allowed)) {
        return null;
    }

    $fileName = uniqid().'_'.time().'_'.rand(1000,9999).'.'.$extension;

    $destination = public_path('uploads/'.$folder);

    if (!File::exists($destination)) {
        File::makeDirectory($destination, 0775, true);
    }

    $file->move($destination, $fileName);

    return 'uploads/'.$folder.'/'.$fileName;
}
}

/*
|--------------------------------------------------------------------------
| FILE URL
|--------------------------------------------------------------------------
*/

if (!function_exists('file_url')) {


    function file_url(?string $path): ?string
    {

        $path = normalize_file_path($path);


        if (!$path) {

            return null;
        }


        if (str_starts_with($path,'http')) {

            return $path;
        }


        return asset($path);
    }
}



/*
|--------------------------------------------------------------------------
| FILE EXISTS
|--------------------------------------------------------------------------
*/

if (!function_exists('file_exists_custom')) {


    function file_exists_custom(?string $path): bool
    {

        $path = normalize_file_path($path);


        if (!$path) {

            return false;
        }


        if (str_starts_with($path,'http')) {

            return true;
        }


        return File::exists(
            public_path($path)
        );
    }
}



/*
|--------------------------------------------------------------------------
| DELETE FILE
|--------------------------------------------------------------------------
*/

if (!function_exists('delete_uploaded_file')) {

    function delete_uploaded_file(?string $path): bool
    {

        $path =
            normalize_file_path($path);

        if (!$path) {

            return false;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY FILE
            |--------------------------------------------------------------------------
            */

            if (str_starts_with($path, 'http')) {

                $cloudinary =
                    new Cloudinary(
                        env('CLOUDINARY_URL')
                    );

                $urlPath =
                    parse_url(
                        $path,
                        PHP_URL_PATH
                    );

                /*
                |--------------------------------------------------------------------------
                | REMOVE VERSION
                |--------------------------------------------------------------------------
                |
                | Example:
                | /demo/raw/upload/v1780123019/pan_aadhaar/file.pdf
                |
                */

                $parts =
                    explode(
                        '/',
                        trim($urlPath, '/')
                    );

                $uploadIndex =
                    array_search(
                        'upload',
                        $parts
                    );

                if ($uploadIndex === false) {

                    return false;
                }

                $publicIdParts =
                    array_slice(
                        $parts,
                        $uploadIndex + 2
                    );

                $publicId =
                    implode(
                        '/',
                        $publicIdParts
                    );

                /*
                |--------------------------------------------------------------------------
                | REMOVE EXTENSION
                |--------------------------------------------------------------------------
                */

                $publicId =
                    preg_replace(
                        '/\.[^.]+$/',
                        '',
                        $publicId
                    );

                /*
                |--------------------------------------------------------------------------
                | DETECT RESOURCE TYPE
                |--------------------------------------------------------------------------
                */

                $resourceType =
                    str_contains(
                        strtolower($path),
                        '/raw/upload/'
                    )
                    ||
                    str_ends_with(
                        strtolower($path),
                        '.pdf'
                    )

                    ? 'raw'

                    : 'image';

                $cloudinary
                    ->uploadApi()
                    ->destroy(

                        $publicId,

                        [

                            'resource_type' =>
                                $resourceType

                        ]

                    );

                return true;
            }

            /*
            |--------------------------------------------------------------------------
            | LOCAL FILE
            |--------------------------------------------------------------------------
            */

            if (
                File::exists(
                    public_path($path)
                )
            ) {

                File::delete(
                    public_path($path)
                );

                return true;
            }

            return false;

        } catch (\Throwable $e) {

            logger()->error(

                'File delete failed',

                [

                    'path' =>
                        $path,

                    'error' =>
                        $e->getMessage()

                ]

            );

            return false;
        }
    }
}



/*
|--------------------------------------------------------------------------
| LOCAL DIRECTORIES
|--------------------------------------------------------------------------
*/

if (!function_exists('ensure_upload_directories')) {


    function ensure_upload_directories(): void
    {

        if (is_vercel()) {

            return;
        }


        foreach ([

            'pan/photo',

            'pan/signature',

            'pan/aadhaar',

            'pan/dob-proof',

            'pan/document'

        ] as $folder) {


            $path =
                public_path(
                    'uploads/'.$folder
                );


            if (!File::exists($path)) {


                File::makeDirectory(

                    $path,

                    0775,

                    true

                );
            }
        }
    }
}
