<?php

namespace App\Repositories;

use App\Models\Admission;
use App\Models\FeeStructure;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    /* ================= FIND ADMISSION ================= */
    public function findAdmission($id)
    {
        return Admission::with('studentClass')->findOrFail($id);
    }

    /* ================= GET PAYMENT PAGE DATA ================= */
    public function getPaymentPageData($id, $year = null)
    {
        $admission = $this->findAdmission($id);

        // Academic Years
        $years = $this->getAcademicYears($admission->class_id);

        // Default year
        $selectedYear = $year ?? $years->first();

        $fees = $this->getFeesByClassAndYear(
            $admission->class_id,
            $selectedYear
        );

        // UPI
        $upi = $this->getActiveUpi();

        return [
            'admission'     => $admission,
            'fees'          => $fees,
            'years'         => $years,
            'selectedYear'  => $selectedYear,
            'upi'           => $upi
        ];
    }

    /* ================= PROCESS PAYMENT (MAIN LOGIC) ================= */
    public function processPayment($id, array $data)
    {
        $admission = $this->findAdmission($id);

        $year = $data['academic_year'];

        /* LOCK CHECK */
        if ($this->isPaymentLocked($admission, $year)) {
            throw new \Exception("Payment already locked for another academic year");
        }

        $total = $data['total_fee'];
        $paid  = $data['paid_amount'];
        $due   = $total - $paid;

        /* BREAKDOWN */
        $breakdown = $this->buildBreakdown(
            $admission->class_id,
            $year,
            $data['selected_services']
        );

        /* SAVE */
        $admission->update([

            'total_fee'         => $total,
            'paid_amount'       => $paid,
            'due_amount'        => $due,

            'payment_type'      => $due > 0 ? 'partial' : 'full',

            // 🔥 FIXED (REAL FLOW)
            'status'            => 'payment_submitted',
            'verification_status' => 'pending',

            'payment_id'        => 'PAY' . time(),
            'paid_at'           => now(),

            'academic_year'     => $year,

            'selected_services' => $data['selected_services'],
            'fee_breakdown'     => $breakdown,

            // 🔥 IMPORTANT (YOU MISSED THIS)
            'utr_no'            => $data['utr_no'] ?? null,
            'payment_screenshot'=> $data['payment_screenshot'] ?? null,
        ]);

        return $admission->fresh();
    }

    /* ================= BREAKDOWN ================= */
    private function buildBreakdown($classId, $year, $selected)
    {
        $fees = $this->getFeesByClassAndYear($classId, $year);

        $result = [];

        /* MANDATORY */
        foreach ($fees['mandatory'] as $fee) {
            $result[] = [
                'type'   => $fee->fee_type,
                'amount' => $fee->amount
            ];
        }

        /* OPTIONAL */
        foreach ($fees['optional'] as $fee) {

            if (isset($selected[$fee->fee_type])) {
                $result[] = [
                    'type'   => $fee->fee_type,
                    'amount' => $fee->amount
                ];
            }
        }

        return $result;
    }

    /* ================= GET FEES ================= */
    public function getFeesByClass($classId)
    {
        $latestYear = FeeStructure::where('class_id', $classId)
            ->max('academic_year');

        return $this->getFeesByClassAndYear($classId, $latestYear);
    }

    public function getFeesByClassAndYear($classId, $year)
    {
        $fees = FeeStructure::where('class_id', $classId)
            ->where('academic_year', $year)
            ->select('fee_type', 'amount', 'is_mandatory', 'academic_year')
            ->get();

        return [
            'mandatory' => $fees->where('is_mandatory', 1)->values(),
            'optional'  => $fees->where('is_mandatory', 0)->values(),
        ];
    }

    /* ================= GET YEARS ================= */
    public function getAcademicYears($classId)
    {
        return FeeStructure::where('class_id', $classId)
            ->pluck('academic_year')
            ->unique()
            ->sortDesc()
            ->values();
    }

    /* ================= GET ACTIVE UPI ================= */
    public function getActiveUpi()
    {
        return DB::table('upi_settings')
            ->where('is_active', 1)
            ->first();
    }

    /* ================= LOCK CHECK ================= */
    public function isPaymentLocked($admission, $year)
    {
        return $admission->academic_year &&
               $admission->academic_year !== $year;
    }

    /* ================= GET EMAILS ================= */
    public function getEmails($admission)
    {
        return collect([
            $admission->father_email,
            $admission->mother_email
        ])->filter()->unique();
    }
}