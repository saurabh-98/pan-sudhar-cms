<?php

namespace App\Services;

use App\Repositories\PopupOfferRepository;
use App\DTO\PopupOfferDTO;
use Illuminate\Support\Facades\File;

class PopupOfferService
{
    public function __construct(
        protected PopupOfferRepository $repo
    ) {}

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function store(PopupOfferDTO $dto)
    {
        return $this->repo->create($dto->toArray());
    }

    public function update($id, PopupOfferDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete($id)
    {
        return $this->repo->delete($id);
    }
}