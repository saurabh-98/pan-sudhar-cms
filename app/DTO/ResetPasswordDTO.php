<?php

namespace App\DTO;

class ResetPasswordDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $token
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            $request->email,
            $request->password,
            $request->token
        );
    }
}