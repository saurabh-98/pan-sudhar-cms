<?php

namespace App\Services;

use App\Repositories\FeatureRepository;
use App\DTO\FeatureDTO;
use App\Models\Feature;
use Illuminate\Support\Facades\Cache; 


class FeatureService
{
    protected $repo;

    public function __construct(FeatureRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        $data = $this->repo->getAll();
         Cache::forget('home_page_data');

        return $data;
    }

    public function store(FeatureDTO $dto)
    {
        $data = $this->repo->store($dto->toArray());
         Cache::forget('home_page_data');

        return $data;
    }

    public function update(Feature $feature, FeatureDTO $dto)
    {
        $data = $this->repo->update($feature, $dto->toArray());

         Cache::forget('home_page_data');

        return $data;
    }

    public function delete(Feature $feature)
    {
        $data = $this->repo->delete($feature);
         Cache::forget('home_page_data');

        return $data;
    }
}