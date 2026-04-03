<?php

namespace App\Services;

use App\Repositories\TableRepository;

class TableService
{
    protected $repo;

    public function __construct(TableRepository $repo)
    {
        $this->repo = $repo;
    }

    /* =========================
       LIST
    ========================= */
    public function list()
    {
        return $this->repo->getAll();
    }

    /* =========================
       CREATE
    ========================= */
    public function create(array $data)
    {
        return $this->repo->create([
            'name' => $data['name'],
            'capacity' => $data['capacity'],
            'is_active' => 1
        ]);
    }

    /* =========================
       UPDATE
    ========================= */
    public function update($id, array $data)
    {
        return $this->repo->update($id, [
            'name' => $data['name'],
            'capacity' => $data['capacity']
        ]);
    }

    /* =========================
       DELETE
    ========================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* =========================
       TOGGLE STATUS
    ========================= */
    public function toggle($id)
    {
        $table = $this->repo->find($id);

        $status = !$table->is_active;

        return $this->repo->update($id, [
            'is_active' => $status
        ]);
    }
}