<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    /* ================= GET ALL ================= */
    public function getAll()
    {
        return Message::with([
                'sender:id,name',
                'receiver:id,name'
            ])
            ->select('id','sender_id','receiver_id','message','is_read','created_at')
            ->latest()
            ->paginate(20);
    }

    /* ================= STORE ================= */
    public function store(array $data)
    {
        return Message::create($data);
    }

    /* ================= MARK AS READ ================= */
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);

        $message->update([
            'is_read' => true
        ]);

        return $message;
    }

    /* ================= DELETE ================= */
    public function delete($id)
    {
        return Message::destroy($id);
    }
}