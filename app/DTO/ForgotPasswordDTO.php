<?php

namespace App\DTO;

class ForgotPasswordDTO
{
    public function __construct(public string $email) {}

    public static function fromRequest($request)
    {
        return new self($request->email);
    }
}