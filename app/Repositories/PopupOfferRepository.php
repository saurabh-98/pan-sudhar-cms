<?php

namespace App\Repositories;

use App\Models\PopupOffer;

class PopupOfferRepository
{
    public function getAll()
    {
        return PopupOffer::latest()->get();
    }

    public function find($id)
    {
        return PopupOffer::findOrFail($id);
    }

    public function create(array $data)
    {
        return PopupOffer::create($data);
    }

    public function update($id, array $data)
    {
        $popup = $this->find($id);
        $popup->update($data);
        return $popup;
    }

    public function delete($id)
    {
        $popup = $this->find($id);
        $popup->delete();
    }
}