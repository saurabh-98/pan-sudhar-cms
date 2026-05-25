<?php

namespace App\Repositories;

use App\Models\TransportStop;

class TransportStopRepository
{
    public function all()
    {
        return TransportStop::with('route')
            ->orderBy('stop_order')
            ->get();
    }

    public function create(array $data)
    {
        return TransportStop::create($data);
    }

    public function update($id, array $data)
    {
        $stop = TransportStop::findOrFail($id);
        $stop->update($data);

        return $stop;
    }

    public function delete($id)
    {
        return TransportStop::findOrFail($id)->delete();
    }
}