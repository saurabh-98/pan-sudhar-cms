<?php

namespace App\DTO;

class OrderCreateDTO
{
    public function __construct(
        public int $user_id,
        public float $total_price,
        public array $items,
        public ?string $mobile = null,
        public string $order_type = 'outside',
        public ?string $table_number = null,
        public ?string $address = null
    ) {}

   
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'total' => $this->total_price,
            'mobile' => $this->mobile,
            'order_type' => $this->order_type,
            'table_number' => $this->table_number,
            'address' => $this->address,
        ];
    }
}