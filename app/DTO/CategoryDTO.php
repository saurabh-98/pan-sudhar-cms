<?php

namespace App\DTO;

class CategoryDTO
{
    public $name;
    public $image;

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->image = $request->file('image');
    }
}