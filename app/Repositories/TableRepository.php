<?php

namespace App\Repositories;

use App\Models\Table;

class TableRepository
{
    public function getAll()
    {
        return Table::latest()->get();
    }

    public function find($id)
    {
        return Table::findOrFail($id);
    }

    public function create(array $data)
    {
        return Table::create($data);
    }

    public function update($id, array $data)
    {
        return Table::findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        return Table::findOrFail($id)->delete();
    }
}