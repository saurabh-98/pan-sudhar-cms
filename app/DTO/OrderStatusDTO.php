<?php

namespace App\DTO;

class OrderStatusDTO
{
    public function __construct(
        public string $status
    ) {}
}