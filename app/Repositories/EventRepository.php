<?php

namespace App\Repositories;

use App\Models\Event;

class EventRepository
{
    /* ================= ALL ================= */
    public function all()
    {
        return Event::latest()->get();
    }

    /* ================= FIND ================= */
    public function find($id)
    {
        return Event::findOrFail($id);
    }

    /* ================= CREATE ================= */
    public function create(array $data)
    {
        return Event::create($data);
    }

    /* ================= UPDATE ================= */
    public function update($id, array $data)
    {
        $event = $this->find($id);

        $event->update($data);

        return $event;
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        $event = $this->find($id);

        return $event->delete();
    }

    /* ================= GALLERY ================= */
    public function gallery()
    {
        return Event::whereNotNull('banner')
            ->latest()
            ->get();
    }
}