<?php

namespace App\Services;

use App\DTO\EventDTO;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function __construct(
        protected EventRepository $repo
    ) {}

    /* ================= ALL ================= */
    public function getAll()
    {
        return $this->repo->all();
    }

    /* ================= FIND ================= */
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /* ================= CREATE ================= */
    public function create(EventDTO $dto)
    {
        DB::beginTransaction();

        try {

            $event = $this->repo->create(
                $dto->toArray()
            );

            DB::commit();

            return $event;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* ================= UPDATE ================= */
    public function update($id, EventDTO $dto)
    {
        DB::beginTransaction();

        try {

            $event = $this->repo->update(
                $id,
                $dto->toArray()
            );

            DB::commit();

            return $event;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* ================= GALLERY ================= */
    public function getGallery()
    {
        return $this->repo->gallery();
    }
}