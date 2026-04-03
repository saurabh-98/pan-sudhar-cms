<?php
namespace App\DTO;

class CartDTO
{
    public $user_id;
    public $menu_id;
    public $qty;

    public function __construct($user_id, $menu_id, $qty = 1)
    {
        $this->user_id = $user_id;
        $this->menu_id = $menu_id;
        $this->qty = $qty;
    }
}