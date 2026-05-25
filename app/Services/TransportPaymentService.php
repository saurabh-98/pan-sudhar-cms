<?php

namespace App\Services;

use App\DTO\TransportPaymentDTO;
use App\Repositories\TransportPaymentRepository;

class TransportPaymentService
{
    public function __construct(
        protected TransportPaymentRepository $repo
    ) {}

    /* ================= LIST ================= */
    public function getAll()
    {
        return $this->repo->all();
    }

    /* ================= DROPDOWN ================= */
    public function getPendingTransports()
    {
        return $this->repo->getPendingTransports();
    }

    /* ================= PAY ================= */
    public function pay(TransportPaymentDTO $dto)
    {
        $transport = $this->repo->getTransport($dto->student_transport_id);

        // 🚨 Prevent invalid record
        if (!$transport) {
            throw new \Exception('Invalid transport record');
        }

        // 🚨 Prevent overpayment
        if ($dto->amount > $transport->due_amount) {
            throw new \Exception('Payment exceeds due amount');
        }

        // 🚨 Prevent zero payment
        if ($dto->amount <= 0) {
            throw new \Exception('Invalid payment amount');
        }

        // ✅ Create payment
        $payment = $this->repo->create($dto->toArray());

        // ✅ Update due & paid
        $this->repo->updateTransportAmounts(
            $dto->student_transport_id,
            $dto->amount
        );

        return $payment;
    }
}