<?php
namespace App\DTO;

class UserDTO
{
    public function __construct(
        public ?string $name,
        public string $email,
        public ?string $password = null, // ✅ FIX (nullable)
        public ?string $role = 'customer',
        public bool $first_login = false // ✅ ADD (important)
    ) {}

    /* ================= REGISTER ================= */
    public static function fromRegister($request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->password,
            'customer',
            false
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
            false
        );
    }

    /* ================= ADMIN CREATE ================= */
    public static function fromAdmin($request): self
    {
        // 👉 CUSTOMER → force password reset
        if ($request->role === 'customer') {
            return new self(
                $request->name,
                $request->email,
                null, // ❌ no password
                'customer',
                true  // ✅ must reset password
            );
        }

        return new self(
            $request->name,
            $request->email,
            $request->password,
            $request->role,
            false
        );
    }

    /* ================= UPDATE ================= */
    public static function fromUpdate($request): self
    {
        return new self(
            $request->name,
            $request->email,
            $request->password ?? null, // ✅ optional
            $request->role,
            false
        );
    }
}