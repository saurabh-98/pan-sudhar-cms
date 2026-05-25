<?php

namespace App\Services;

use App\Repositories\MessageRepository;

class MessageService
{
    protected $repo;

    public function __construct(MessageRepository $repo)
    {
        $this->repo = $repo;
    }

    /* ================= GET ALL ================= */
    public function getAll()
    {
        return $this->repo->getAll();
    }

    /* ================= SEND MESSAGE ================= */
    public function send(array $data)
    {
        // Optional: you can add business logic here later
        return $this->repo->store($data);
    }

    /* ================= MARK AS READ ================= */
    public function markAsRead($id)
    {
        return $this->repo->markAsRead($id);
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* ================= OPTIONAL (FUTURE READY) ================= */

    // Get messages for a specific user (chat system)
    public function getByUser($userId)
    {
        return $this->repo->getAll()
            ->where('receiver_id', $userId);
    }
}