<?php

namespace App\Repositories;

use App\Models\State;

class StateRepository
{
    public function getAll()
    {
        return State::latest()->get();
    }

    public function create(array $data): State
    {
        return State::create($data);
    }

    public function delete($id)
    {
        return State::findOrFail($id)->delete();
    }
}