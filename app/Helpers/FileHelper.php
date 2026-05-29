<?php

use Illuminate\Support\Facades\File;


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
| NORMALIZE FILE PATH
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


        if (!$file || !$file->isValid()) {


            logger()->error(
                'Invalid uploaded file'
            );


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



        if (!in_array($extension, $allowed)) {


            logger()->error(

                'Invalid extension',

                [
                    'extension'=>$extension
                ]

            );


            return null;
        }



        $fileName =

            uniqid()

            .'_'

            .time()

            .'_'

            .rand(1000,9999);



        /*
        |--------------------------------------------------------------------------
        | VERCEL CLOUDINARY
        |--------------------------------------------------------------------------
        */


        if (is_vercel()) {


            try {


                $realPath = $file->getRealPath();



                if (!$realPath) {


                    logger()->error(
                        'Cloudinary temp file missing'
                    );


                    return null;
                }



                logger()->info(

                    'Cloudinary Upload Start',

                    [

                        'path'=>$realPath,

                        'folder'=>$folder

                    ]

                );



                $upload = cloudinary()

                    ->uploadApi()

                    ->upload(

                        $realPath,


                        [

                            'folder' => str_replace(
                                '/',
                                '_',
                                $folder
                            ),


                            'public_id' =>
                                $fileName,


                            'resource_type'
                                =>
                                'auto'

                        ]

                    );




                logger()->info(

                    'Cloudinary Upload Done',

                    [

                        'url' =>
                            $upload['secure_url']
                            ?? null

                    ]

                );




                return $upload['secure_url']
                    ?? null;




            } catch(\Throwable $e) {



                logger()->error(

                    'Cloudinary Failed',

                    [

                        'message' =>
                            $e->getMessage(),

                        'file'=>
                            $e->getFile(),

                        'line'=>
                            $e->getLine()

                    ]

                );



                return null;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | LOCAL STORAGE
        |--------------------------------------------------------------------------
        */


        $localName =

            $fileName.'.'.$extension;



        $destination = public_path(

            'uploads/'.$folder

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

            $localName

        );



        return

            'uploads/'

            .$folder

            .'/'

            .$localName;

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
| EXISTS
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
| DELETE
|--------------------------------------------------------------------------
*/

if (!function_exists('delete_uploaded_file')) {


    function delete_uploaded_file(?string $path): bool
    {

        $path = normalize_file_path($path);


        if (!$path) {

            return false;
        }


        try {


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
                            'resource_type'=>'auto'
                        ]

                    );


                return true;
            }



            if (File::exists(public_path($path))) {


                File::delete(
                    public_path($path)
                );


                return true;
            }


            return false;


        } catch(\Throwable $e) {


            logger()->error(

                'Delete failed',

                [
                    'error'=>$e->getMessage()
                ]

            );


            return false;
        }
    }
}





/*
|--------------------------------------------------------------------------
| CREATE LOCAL FOLDERS
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