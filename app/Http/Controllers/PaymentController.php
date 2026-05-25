<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Services\PaymentService;
use App\DTO\PaymentDTO;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    /* ================= SHOW PAYMENT PAGE ================= */
    public function show(Request $request, $id)
    {
        try {

            $year = $request->query('year');

            $data = $this->service->getPaymentPageData($id, $year);
            

            if (!$data || empty($data['admission'])) {

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid admission record'
                    ], 404);
                }

                return abort(404, 'Invalid admission record');
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('payment.index', $data);

        } catch (\Throwable $e) {

            Log::error('Payment Page Error', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return abort(500, 'Unable to load payment page');
        }
    }

    /* ================= CONFIRM PAYMENT ================= */
    public function confirm(PaymentRequest $request, $id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | HANDLE PAYMENT SCREENSHOT
            |--------------------------------------------------------------------------
            */

            $filename = null;

            if ($request->hasFile('payment_screenshot')) {

                $file = $request->file('payment_screenshot');

                /*
                |--------------------------------------------------------------------------
                | ALLOWED MIME TYPES
                |--------------------------------------------------------------------------
                */

                $allowedMime = [

                    'image/jpeg',

                    'image/png',

                    'application/pdf'
                ];

                /*
                |--------------------------------------------------------------------------
                | REAL MIME CHECK
                |--------------------------------------------------------------------------
                */

                $realMime = mime_content_type(

                    $file->getRealPath()
                );

                if (

                    !in_array(

                        $realMime,

                        $allowedMime
                    )

                ) {

                    throw new \Exception(

                        'Invalid payment screenshot type'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | FILE SIZE CHECK
                |--------------------------------------------------------------------------
                */

                if (

                    $file->getSize()

                    > 2 * 1024 * 1024
                ) {

                    throw new \Exception(

                        'Payment screenshot too large'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | STORE FILE
                |--------------------------------------------------------------------------
                */

                $filename = $file->store(

                    'payments',

                    'public'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE DTO
            |--------------------------------------------------------------------------
            */

            $dto = PaymentDTO::fromRequest($request);

            /*
            |--------------------------------------------------------------------------
            | EXTRA FIELDS
            |--------------------------------------------------------------------------
            */

            $dto->utr_no = $request->utr_no;

            $dto->payment_screenshot = $filename;

            /*
            |--------------------------------------------------------------------------
            | PROCESS PAYMENT
            |--------------------------------------------------------------------------
            */

            $admission = $this->service->processPayment(

                $id,

                $dto
            );

            /*
            |--------------------------------------------------------------------------
            | MESSAGE
            |--------------------------------------------------------------------------
            */

            $message = $admission->due_amount > 0

                ? 'Partial payment submitted for verification'

                : 'Full payment submitted for verification';

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => true,

                'message' => $message,

                'redirect' => route(

                    'payment.success',

                    $admission->id
                )
            ]);

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | LOG ERROR
            |--------------------------------------------------------------------------
            */

            Log::error(

                'Payment Failed',

                [

                    'id' => $id,

                    'error' => $e->getMessage(),

                    'line' => $e->getLine(),

                    'file' => $e->getFile()
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | ERROR RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => false,

                'message' => config('app.debug')

                    ? $e->getMessage()

                    : 'Payment submission failed. Please try again.'
            ], 500);
        }
    }

    
    /* ================= SUCCESS PAGE ================= */
    public function success($id)
    {
        try {

            $admission = $this->service->getAdmission($id);

            if (!$admission) {
                return abort(404, 'Invalid payment record');
            }

            return view('payment.success', compact('admission'));

        } catch (\Throwable $e) {

            Log::error('Payment Success Page Error', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return abort(500, 'Something went wrong');
        }
    }

    /* ================= DOWNLOAD RECEIPT ================= */
    public function receipt($id)
    {
        try {

            $admission = $this->service->getAdmission($id);

            if (!$admission) {
                return abort(404, 'Receipt not found');
            }

            $pdf = Pdf::loadView('pdf.payment_receipt', [
                'admission' => $admission
            ]);

            return $pdf->download('payment_receipt_' . $admission->id . '.pdf');

        } catch (\Throwable $e) {

            Log::error('Receipt Download Error', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return abort(500, 'Unable to download receipt');
        }
    }

    /* ================= AJAX: LOAD FEES BY YEAR ================= */
    public function feesByYear(Request $request, $id)
    {
        try {

            $year = $request->get('year');

            if (!$year) {
                return response()->json([
                    'success' => false,
                    'message' => 'Academic year is required'
                ], 422);
            }

            $data = $this->service->getPaymentPageData($id, $year);

            return response()->json([
                'success' => true,
                'fees' => $data['fees'],
                'year' => $year
            ]);

        } catch (\Throwable $e) {

            Log::error('Fees By Year Error', [
                'id' => $id,
                'year' => $request->get('year'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load fees'
            ], 500);
        }
    }
}