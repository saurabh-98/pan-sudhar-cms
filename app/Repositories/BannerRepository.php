<?php
namespace App\Repositories;

use App\Models\Banner;

class BannerRepository
{
    public function getAll()
    {
        return Banner::select('id','title','subtitle','button_text','image')
            ->latest()
            ->get();
    }

    public function find($id)
    {
        return Banner::find($id);
    }

    public function create($data)
    {
        return Banner::create($data);
    }

    public function update($banner, $data)
    {
        return $banner->update($data);
    }

    public function delete($banner)
    {
        return $banner->delete();
    }
}