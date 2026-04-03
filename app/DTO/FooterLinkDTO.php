<?php

namespace App\DTO;

class FooterLinkDTO
{
    public $section;
    public $name;
    public $url;
    public $sort_order;

    public function __construct($request)
    {
        $this->section = $request->section;
        $this->name = $request->name;
        $this->url = $request->url;
        $this->sort_order = $request->sort_order ?? 0;
    }

    public function toArray()
    {
        return [
            'section' => $this->section,
            'name' => $this->name,
            'url' => $this->url,
            'sort_order' => $this->sort_order,
        ];
    }
}