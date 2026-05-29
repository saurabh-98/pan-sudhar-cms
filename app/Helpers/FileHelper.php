<?php

use Illuminate\Support\Facades\File;


/*
|--------------------------------------------------------------------------
| CHECK VERCEL
|--------------------------------------------------------------------------
*/

if (!function_exists('is_vercel')) {

    function is_vercel(): bool
    {
        return 
            env('APP_ENV') === 'production'
            ||
            env('VERCEL') === '1'
            ||
            env('FILESYSTEM_DISK') === 'cloudinary';
    }
}

/*
|--------------------------------------------------------------------------
| NORMALIZE LOCAL PATH
|--------------------------------------------------------------------------
*/

if (!function_exists('normalize_file_path')) {

    function normalize_file_path(
        ?string $path
    ): ?string {


        if (!$path) {

            return null;
        }


        // Cloudinary or external URL
        if (str_starts_with($path, 'http')) {

            return $path;
        }


        // already correct
        if (str_starts_with($path, 'uploads/')) {

            return $path;
        }


        // old saved value:
        // pan/photo/a.jpg
        return 'uploads/' . $path;

    }
}




/*
|--------------------------------------------------------------------------
| STORE FILE
|--------------------------------------------------------------------------
*/

if (!function_exists('store_uploaded_file')) {


    function store_uploaded_file(
        $file,
        string $folder
    ): ?string {


        if (
            !$file ||
            !$file->isValid()
        ) {

            return null;
        }


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


        if (!in_array($extension,$allowed)) {

            return null;
        }



        $fileName =

            uniqid()

            . '_'

            . time()

            . '_'

            . rand(1000,9999)

            . '.'

            . $extension;




        /*
        |--------------------------------------------------------------------------
        | VERCEL - CLOUDINARY
        |--------------------------------------------------------------------------
        */

        if (is_vercel()) {


            try {


                $upload = cloudinary()
                    ->uploadApi()
                    ->upload(

                        $file->getRealPath(),

                        [

                            'folder' =>
                                $folder,

                            'public_id' =>
                                pathinfo(
                                    $fileName,
                                    PATHINFO_FILENAME
                                ),

                            'resource_type' =>
                                'auto'

                        ]

                    );


                return $upload['secure_url']
                    ?? null;


            } catch(Throwable $e) {


                logger()->error(

                    'Cloudinary Upload Failed',

                    [

                        'error'=>
                            $e->getMessage()

                    ]

                );


                return null;

            }
        }




        /*
        |--------------------------------------------------------------------------
        | LOCAL
        |--------------------------------------------------------------------------
        */

        $destination = public_path(
            'uploads/' . $folder
        );


        if (!File::exists($destination)) {


            File::makeDirectory(

                $destination,

                0775,

                true

            );

        }



        $file->move(

            $destination,

            $fileName

        );


        return

            'uploads/'

            . $folder

            . '/'

            . $fileName;

    }

}




/*
|--------------------------------------------------------------------------
| GET FILE URL
|--------------------------------------------------------------------------
*/

if (!function_exists('file_url')) {


    function file_url(
        ?string $path
    ): ?string {


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
| CHECK EXISTS
|--------------------------------------------------------------------------
*/

if (!function_exists('file_exists_custom')) {


    function file_exists_custom(
        ?string $path
    ): bool {


        $path = normalize_file_path($path);


        if (!$path) {

            return false;

        }


        // Cloudinary
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


    function delete_uploaded_file(
        ?string $path
    ): bool {


        $path = normalize_file_path($path);


        if (!$path) {

            return false;
        }



        try {


            /*
            |--------------------------------------------------------------------------
            | CLOUDINARY
            |--------------------------------------------------------------------------
            */

            if (str_starts_with($path,'http')) {


                $info = pathinfo(

                    parse_url(
                        $path,
                        PHP_URL_PATH
                    )

                );


                cloudinary()
                    ->uploadApi()
                    ->destroy(

                        $info['filename'],

                        [

                            'resource_type'
                            =>
                            'auto'

                        ]

                    );


                return true;

            }




            /*
            |--------------------------------------------------------------------------
            | LOCAL
            |--------------------------------------------------------------------------
            */

            $local = public_path($path);


            if (File::exists($local)) {


                File::delete($local);


                return true;

            }


            return false;



        } catch(Throwable $e) {


            logger()->error(

                'File Delete Failed',

                [

                    'error'=>
                        $e->getMessage()

                ]

            );


            return false;

        }

    }

}




/*
|--------------------------------------------------------------------------
| ENSURE DIRECTORIES
|--------------------------------------------------------------------------
*/

if (!function_exists('ensure_upload_directories')) {


    function ensure_upload_directories(): void
    {


        if (is_vercel()) {

            return;

        }


        $folders = [

            'pan/photo',

            'pan/signature',

            'pan/aadhaar',

            'pan/dob-proof',

            'pan/document'

        ];


        foreach ($folders as $folder) {


            $path = public_path(

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