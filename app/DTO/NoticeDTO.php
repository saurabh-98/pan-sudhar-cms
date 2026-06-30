<?php
namespace App\DTO;

class NoticeDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $publish_date,
        public ?string $expiry_date
    ){}

    public static function fromRequest($r): self
    {
        return new self(
            $r->title,
            $r->description,
            $r->publish_date,
            $r->expiry_date
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}