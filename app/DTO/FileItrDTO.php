<?php

namespace App\DTO;

use App\Http\Requests\StoreItrRequest;
use Illuminate\Http\UploadedFile;

class FileItrDTO
{
    public function __construct(

        public readonly string $name,

        public readonly string $mobile,

        public readonly string $email,

        public readonly ?string $remarks,

        public readonly ?UploadedFile $aadhaarFront,

        public readonly ?UploadedFile $aadhaarBack,

        public readonly ?UploadedFile $panCard,

        public readonly float $charge = 99.00

    ) {}

    public static function fromRequest(
        StoreItrRequest $request
    ): self {

        return new self(

            name:
                trim($request->name),

            mobile:
                trim($request->mobile),

            email:
                trim($request->email),

            remarks:
                $request->remarks
                    ? trim($request->remarks)
                    : null,

            aadhaarFront:
                $request->file('aadhaar_front'),

            aadhaarBack:
                $request->file('aadhaar_back'),

            panCard:
                $request->file('pan_card'),

            charge: 99.00

        );
    }

    public function toArray(
        string $aadhaarFront,
        string $aadhaarBack,
        string $panCard
    ): array {

        return [

            'user_id' =>
                auth()->id(),

            'name' =>
                $this->name,

            'mobile' =>
                $this->mobile,

            'email' =>
                $this->email,

            'remarks' =>
                $this->remarks,

            'aadhaar_front' =>
                $aadhaarFront,

            'aadhaar_back' =>
                $aadhaarBack,

            'pan_card' =>
                $panCard,

            'charge' =>
                $this->charge,

            'status' =>
                'pending',

            'created_at' =>
                now(),

            'updated_at' =>
                now()

        ];
    }
}