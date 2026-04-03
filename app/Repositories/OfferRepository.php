<?php
namespace App\Repositories;

use App\Models\Offer;

class OfferRepository
{
    public function all()
    {
        return Offer::latest()->get();
    }


    public function getActive()
    {
        return Offer::where('is_active', 1)
                    ->orderBy('id','desc')
                    ->get();
    }

    
    public function find($id)
    {
        return Offer::findOrFail($id);
    }

     public function findOffer($id)
    {
        return Offer::find($id);
    }

    public function create($data)
    {
        return Offer::create($data);
    }

    public function update($id, $data)
    {
        return Offer::findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        return Offer::destroy($id);
    }

    
}