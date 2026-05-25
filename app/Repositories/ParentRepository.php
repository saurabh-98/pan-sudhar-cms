<?php
namespace App\Repositories;

use App\Models\ParentModel;

class ParentRepository
{
    public function all()
    {
        return ParentModel::latest()->get();
    }

    public function store($data)
    {
        return ParentModel::create($data);
    }

    public function update($id,$data)
    {
        $p = ParentModel::findOrFail($id);
        $p->update($data);
        return $p;
    }

    public function delete($id)
    {
        return ParentModel::destroy($id);
    }
}