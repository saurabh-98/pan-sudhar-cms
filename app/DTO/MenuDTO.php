<?php

namespace App\DTO;

class MenuDTO
{
    public $name;
    public $price;
    public $category_id;
    public $image;
    public $description;
    public $specifications;

    public function __construct($request)
    {
        $this->name = $request->name;
        $this->price = $request->price;
        $this->category_id = $request->category_id;

        // ✅ NEW FIELDS
        $this->description = $request->description;
        $this->specifications = $request->specifications;

        // ✅ FIXED IMAGE HANDLING
        if ($request->hasFile('image')) {
            $this->image = $request->file('image')->store('menus', 'public');
        } else {
            $this->image = null;
        }
    }
}