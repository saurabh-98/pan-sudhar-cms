<?php

namespace App\Services;

use App\Repositories\DistrictRepository;
use App\DTO\DistrictDTO;
use Illuminate\Support\Facades\DB;

class DistrictService
{
    protected $repo;

    public function __construct(DistrictRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(DistrictDTO $dto)
    {
        DB::beginTransaction();

        try {
            $district = $this->repo->create($dto->toArray());
            DB::commit();
            return $district;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* 🔥 FOR AJAX DROPDOWN */
    public function getByState($stateId)
    {
        return $this->repo->getByState($stateId);
    }
}