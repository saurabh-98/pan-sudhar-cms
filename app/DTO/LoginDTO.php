<?php

namespace App\DTO;

class LoginDTO
{
    public string $email;
    public string $password;
    public bool $remember;

    public function __construct(string $email, string $password, bool $remember)
    {
        $this->email = $email;
        $this->password = $password;
        $this->remember = $remember;
    }

    public static function fromRequest($request): self
    {
        return new self(
            $request->email,
            $request->password,
            $request->has('remember')
        );
    }
}