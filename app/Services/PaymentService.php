<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use App\DTO\PaymentDTO;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;

class PaymentService
{
    protected $repo;

    public function __construct(PaymentRepository $repo)
    {
        $this->repo = $repo;
    }

    /* ================= GET PAYMENT PAGE ================= */
    public function getPaymentPageData($id, $year = null)
    {
        return $this->repo->getPaymentPageData($id, $year);
    }

    /* ================= PROCESS PAYMENT ================= */
    public function processPayment($id, PaymentDTO $dto)
    {
        DB::beginTransaction();

        try {

            /* 🔥 DELEGATE EVERYTHING TO REPO */
            $admission = $this->repo->processPayment(
                $id,
                $dto->toArray()
            );

            /* ================= PDF ================= */
            $pdf = $this->generateReceipt($admission);

            /* ================= EMAIL ================= */
            $emails = $this->repo->getEmails($admission);

            foreach ($emails as $email) {
                Mail::to($email)->send(
                    new PaymentReceiptMail($admission, $pdf)
                );
            }

            DB::commit();

            return $admission;

        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }

    /* ================= PDF ================= */
    private function generateReceipt($admission)
    {
        return Pdf::loadView('pdf.payment_receipt', [
            'admission' => $admission
        ])->output();
    }

    /* ================= GET ================= */
    public function getAdmission($id)
    {
        return $this->repo->findAdmission($id);
    }

    /* ================= AJAX: GET FEES BY YEAR ================= */
    public function getFeesByYear($id, $year)
    {
        $data = $this->repo->getPaymentPageData($id, $year);

        return $data['fees'];
    }
}