<?php

namespace App\DTO;

use Illuminate\Http\Request;

class BankDocsDTO
{
    public function __construct(

        public readonly ?string $service_code,

        public readonly ?string $title,

        public readonly ?string $description,

        public readonly mixed $pdf,

        public readonly bool $is_active,

    ) {}

    /**
     * Create DTO from Request
     */
    public static function fromRequest(Request $request): self
    {
        return new self(

            service_code: $request->service_code,

            title: $request->title,

            description: $request->description,

            pdf: $request->file('pdf'),

            is_active: (bool) $request->boolean('is_active'),

        );
    }

    /**
     * Convert DTO to Array
     */
    public function toArray(): array
    {
        return [

            'service_code' => $this->service_code,

            'title' => $this->title,

            'description' => $this->description,

            'is_active' => $this->is_active,

        ];
    }
}