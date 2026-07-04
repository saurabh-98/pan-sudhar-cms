<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(

        public ?string $name,

        public string $email,

        public ?string $password = null,

        public ?string $role = 'customer',

        public bool $first_login = false,

        public int $status = 1

    ) {}

    /* ================= REGISTER ================= */

    public static function fromRegister($request): self
    {
        return new self(

            $request->name,

            $request->email,

            $request->password,

            'customer',

            false,

            1

        );
    }

    /* ================= LOGIN ================= */

    public static function fromLogin($request): self
    {
        return new self(

            null,

            $request->email,

            $request->password,

            null,

            false,

            1

        );
    }

    /* ================= ADMIN CREATE ================= */

    public static function fromAdmin($request): self
    {
        if ($request->role === 'customer') {

            return new self(

                $request->name,

                $request->email,

                null,

                'customer',

                true,

                $request->status ?? 1

            );
        }

        return new self(

            $request->name,

            $request->email,

            $request->password,

            $request->role,

            false,

            $request->status ?? 1

        );
    }

    /* ================= UPDATE ================= */

    public static function fromUpdate($request): self
    {
        return new self(

            $request->name,

            $request->email,

            $request->filled('password')
                ? $request->password
                : null,

            $request->role,

            false,

            $request->status ?? 1

        );
    }
}