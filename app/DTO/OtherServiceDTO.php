<?php

namespace App\DTO;

use App\Http\Requests\StoreOtherServiceRequest;
use Illuminate\Http\UploadedFile;

class OtherServiceDTO
{
    public function __construct(

        public readonly int $user_id,

        public readonly ?int $assigned_to,

        public readonly string $service_name,

        public readonly string $service_slug,

        public readonly array $form_data,

        public readonly array $files,

        public readonly float $amount,

        public readonly string $ip_address,

        public readonly string $browser

    ) {}

    /*
    |--------------------------------------------------------------------------
    | FROM REQUEST
    |--------------------------------------------------------------------------
    */

    public static function fromRequest(
        StoreOtherServiceRequest $request
    ): self {

        $excludedFields = [

            '_token',

            'service_name',

            'service_slug',

        ];

        $formData = [];

        foreach (
            $request->except($excludedFields)
            as $key => $value
        ) {

            if (! $request->hasFile($key)) {

                $formData[$key] = $value;
            }
        }

        $files = [];

        foreach (
            $request->allFiles()
            as $key => $file
        ) {

            if (
                $file instanceof UploadedFile
            ) {

                $files[$key] = $file;
            }
        }

        return new self(

            user_id:
                auth()->id(),

            assigned_to:
                null,

            service_name:
                trim(
                    $request->service_name
                ),

            service_slug:
                trim(
                    $request->service_slug
                ),

            form_data:
                $formData,

            files:
                $files,

            amount:
                0,

            ip_address:
                $request->ip(),

            browser:
                (string) $request->userAgent()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | FROM ARRAY
    |--------------------------------------------------------------------------
    */

    public static function fromArray(
        array $data
    ): self {

        return new self(

            user_id:
                (int) (
                    $data['user_id']
                    ?? 0
                ),

            assigned_to:
                $data['assigned_to']
                ?? null,

            service_name:
                $data['service_name']
                ?? '',

            service_slug:
                $data['service_slug']
                ?? '',

            form_data:
                $data['form_data']
                ?? [],

            files:
                $data['files']
                ?? [],

            amount:
                (float) (
                    $data['amount']
                    ?? 0
                ),

            ip_address:
                $data['ip_address']
                ?? request()->ip(),

            browser:
                $data['browser']
                ?? request()->userAgent()

        );
    }

    /*
    |--------------------------------------------------------------------------
    | TO ARRAY
    |--------------------------------------------------------------------------
    */

    public function toArray(): array
    {
        return [

            'user_id' => $this->user_id,

            'assigned_to' => $this->assigned_to,

            'service_name' => $this->service_name,

            'service_slug' => $this->service_slug,

            'form_data' => $this->form_data,

            'amount' => $this->amount,

            'ip_address' => $this->ip_address,

            'browser' => $this->browser,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW ARRAY
    |--------------------------------------------------------------------------
    */

    public function toPreviewArray(): array
    {
        return [

            'service_name' => $this->service_name,

            'service_slug' => $this->service_slug,

            'form_data' => $this->form_data,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | GET FIELD
    |--------------------------------------------------------------------------
    */

    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        return $this->form_data[$key]
            ?? $default;
    }

    /*
    |--------------------------------------------------------------------------
    | HAS FIELD
    |--------------------------------------------------------------------------
    */

    public function has(
        string $key
    ): bool {

        return array_key_exists(
            $key,
            $this->form_data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET FILE
    |--------------------------------------------------------------------------
    */

    public function file(
        string $key
    ): ?UploadedFile {

        return $this->files[$key]
            ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | HAS FILE
    |--------------------------------------------------------------------------
    */

    public function hasFile(
        string $key
    ): bool {

        return isset(
            $this->files[$key]
        );
    }
}