<?php

namespace App\Services;

use App\Repositories\StateRepository;
use App\DTO\StateDTO;
use Illuminate\Support\Facades\DB;

class StateService
{
    protected $repo;

    public function __construct(StateRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(StateDTO $dto)
    {
        DB::beginTransaction();

        try {
            $state = $this->repo->create($dto->toArray());
            DB::commit();
            return $state;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}