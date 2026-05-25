<?php

namespace App\Services;

use App\Repositories\PayrollRepository;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipService
{
    public function __construct(
        protected PayrollRepository $repo
    ) {}

    public function getById($id)
    {
        return $this->repo->findWithEmployee($id);
    }

    public function downloadPdf($id)
    {
        $data = $this->getById($id);

        $pdf = Pdf::loadView('admin.payslip.pdf', compact('data'));

        return $pdf->download('payslip-'.$id.'.pdf');
    }
}