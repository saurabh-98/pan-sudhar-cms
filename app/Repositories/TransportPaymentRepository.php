<?php

namespace App\Repositories;

use App\Models\TransportPayment;
use App\Models\StudentTransport;

class TransportPaymentRepository
{
    /* ================= LIST ================= */
    public function all()
    {
        return TransportPayment::with(['transport.student'])
            ->latest()
            ->get();
    }

    /* ================= CREATE ================= */
    public function create(array $data)
    {
        return TransportPayment::create($data);
    }

    /* ================= GET TRANSPORT ================= */
    public function getTransport($id)
    {
        return StudentTransport::findOrFail($id);
    }

    /* ================= UPDATE DUE ================= */
    public function updateTransportAmounts($transportId, $amount)
    {
        $record = StudentTransport::findOrFail($transportId);

        $record->paid_amount += $amount;
        $record->due_amount  -= $amount;

        if ($record->due_amount <= 0) {
            $record->due_amount = 0;
            $record->status = 'paid';
        }

        $record->save();

        return $record;
    }

    /* ================= DROPDOWN DATA ================= */
    public function getPendingTransports()
    {
        return StudentTransport::with('student')
            ->where('due_amount', '>', 0) // only unpaid
            ->select('id','student_id','due_amount')
            ->get();
    }
}