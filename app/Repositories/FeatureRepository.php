<?php

namespace App\Repositories;

use App\Models\Feature;

class FeatureRepository
{
    public function getAll()
    {
        return Feature::latest()->get();
    }

    public function store(array $data)
    {
        return Feature::create($data);
    }

    public function update(Feature $feature, array $data)
    {
        $feature->update($data);
        return $feature;
    }

    public function delete(Feature $feature)
    {
        return $feature->delete();
    }
}