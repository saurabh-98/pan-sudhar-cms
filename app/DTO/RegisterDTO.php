<?php

namespace App\DTO;

class RegisterDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->password
        );
    }
}