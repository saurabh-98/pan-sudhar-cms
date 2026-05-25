<?php

namespace App\Repositories;

use App\Models\FeeCollection;
use App\Models\Admission;

class FeeCollectionRepository
{
    /*
    |--------------------------------------------------------------------------
    | GET ALL COLLECTIONS / UNIFIED ERP LEDGER
    |--------------------------------------------------------------------------
    */

    public function getAll()
    {
        /*
        |--------------------------------------------------------------------------
        | ADMISSION PAYMENTS
        |--------------------------------------------------------------------------
        */

        $admissions = Admission::with([

                'studentClass:id,name'

            ])
            ->whereNotNull(
                'paid_at'
            )
            ->latest()
            ->get([

                'id',

                'name',

                'registration_no',

                'class_id',

                'paid_amount',

                'payment_type',

                'payment_id',

                'utr_no',

                'paid_at',

                'verification_status'

            ])
            ->map(function($item){

                return [

                    'id' =>

                        'ADM-' . $item->id,

                    'receipt_no' =>

                        $item->payment_id
                        ??
                        'N/A',

                    'student' =>

                        $item->name,

                    'amount' =>

                        $item->paid_amount,

                    'method' =>

                        strtoupper(
                            $item->payment_type
                        ),

                    'date' =>

                        $item->paid_at,

                    'status' =>

                        $item->verification_status,

                    'type' =>

                        'Admission Payment',

                    'class' =>

                        optional(
                            $item->studentClass
                        )->name
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | FEE COLLECTIONS
        |--------------------------------------------------------------------------
        */

        $collections = FeeCollection::with([

                'admission:id,name,registration_no,class_id',

                'admission.studentClass:id,name'

            ])
            ->latest()
            ->get()
            ->map(function($item){

                return [

                    'id' =>

                        'FEE-' . $item->id,

                    'receipt_no' =>

                        $item->receipt_no
                        ??
                        'N/A',

                    'student' =>

                        optional(
                            $item->admission
                        )->name,

                    'amount' =>

                        $item->paid_amount,

                    'method' =>

                        $item->payment_method,

                    'date' =>

                        $item->payment_date,

                    'status' =>

                        $item->status,

                    'type' =>

                        'Fee Collection',

                    'class' =>

                        optional(
                            optional(
                                $item->admission
                            )->studentClass
                        )->name
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | MERGE ERP LEDGER
        |--------------------------------------------------------------------------
        */

        return $admissions

            ->merge($collections)

            ->sortByDesc('date')

            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | STORE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function store(array $data)
    {
        return FeeCollection::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ) {

        $collection =
            FeeCollection::findOrFail($id);

        $collection->update($data);

        return $collection;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function delete(int $id)
    {
        return FeeCollection::destroy($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY ID
    |--------------------------------------------------------------------------
    */

    public function findById(int $id)
    {
        return FeeCollection::with([

                'admission',

                'installment',

                'collector'

            ])
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMISSION COLLECTION HISTORY
    |--------------------------------------------------------------------------
    */

    public function getAdmissionHistory(
        int $admissionId
    ) {

        return FeeCollection::with([

                'collector:id,name'

            ])
            ->where(
                'admission_id',
                $admissionId
            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT COLLECTIONS
    |--------------------------------------------------------------------------
    */

    public function getRecentCollections(
        int $limit = 10
    ) {

        return FeeCollection::with([

                'admission:id,name,registration_no',

                'collector:id,name'

            ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | TODAY COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getTodayCollection()
    {
        $feeCollection = FeeCollection::whereDate(

                'payment_date',

                today()

            )
            ->sum('paid_amount');

        $admissionCollection = Admission::whereDate(

                'paid_at',

                today()

            )
            ->sum('paid_amount');

        return $feeCollection +
               $admissionCollection;
    }

    /*
    |--------------------------------------------------------------------------
    | MONTHLY COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getMonthlyCollection(
        $month = null,
        $year = null
    ) {

        $month =
            $month ?? now()->month;

        $year  =
            $year ?? now()->year;

        $feeCollection = FeeCollection::whereMonth(

                'payment_date',

                $month

            )
            ->whereYear(

                'payment_date',

                $year

            )
            ->sum('paid_amount');

        $admissionCollection = Admission::whereMonth(

                'paid_at',

                $month

            )
            ->whereYear(

                'paid_at',

                $year

            )
            ->sum('paid_amount');

        return $feeCollection +
               $admissionCollection;
    }

    /*
    |--------------------------------------------------------------------------
    | YEARLY COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getYearlyCollection(
        $year = null
    ) {

        $year =
            $year ?? now()->year;

        $feeCollection = FeeCollection::whereYear(

                'payment_date',

                $year

            )
            ->sum('paid_amount');

        $admissionCollection = Admission::whereYear(

                'paid_at',

                $year

            )
            ->sum('paid_amount');

        return $feeCollection +
               $admissionCollection;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getTotalCollection()
    {
        $feeCollection =
            FeeCollection::sum(
                'paid_amount'
            );

        $admissionCollection =
            Admission::sum(
                'paid_amount'
            );

        return $feeCollection +
               $admissionCollection;
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS WISE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getClassWiseCollection(
        int $classId
    ) {

        return FeeCollection::whereHas(

                'admission',

                function ($query) use (
                    $classId
                ) {

                    $query->where(
                        'class_id',
                        $classId
                    );
                }

            )
            ->sum('paid_amount');
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT MODE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getPaymentModeCollection(
        string $method
    ) {

        return FeeCollection::where(

                'payment_method',

                $method

            )
            ->sum('paid_amount');
    }

    /*
    |--------------------------------------------------------------------------
    | DATE RANGE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getDateRangeCollection(
        $from,
        $to
    ) {

        return FeeCollection::whereBetween(

                'payment_date',

                [$from, $to]

            )
            ->sum('paid_amount');
    }

    /*
    |--------------------------------------------------------------------------
    | DATE RANGE HISTORY
    |--------------------------------------------------------------------------
    */

    public function getDateRangeHistory(
        $from,
        $to
    ) {

        return FeeCollection::with([

                'admission:id,name,registration_no',

                'collector:id,name'

            ])
            ->whereBetween(

                'payment_date',

                [$from, $to]

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT SEARCH
    |--------------------------------------------------------------------------
    */

    public function findByReceiptNo(
        string $receiptNo
    ) {

        return FeeCollection::where(

                'receipt_no',

                $receiptNo

            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION SEARCH
    |--------------------------------------------------------------------------
    */

    public function findByTransactionId(
        string $transactionId
    ) {

        return FeeCollection::where(

                'transaction_id',

                $transactionId

            )
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR HISTORY
    |--------------------------------------------------------------------------
    */

    public function getCollectorHistory(
        int $userId
    ) {

        return FeeCollection::where(

                'collected_by',

                $userId

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FAILED PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function getFailedPayments()
    {
        return FeeCollection::where(

                'status',

                'failed'

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | REFUNDED PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function getRefundedPayments()
    {
        return FeeCollection::where(

                'status',

                'refunded'

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | COUNT METHODS
    |--------------------------------------------------------------------------
    */

    public function countTodayCollections()
    {
        $feeCount = FeeCollection::whereDate(

                'payment_date',

                today()

            )
            ->count();

        $admissionCount = Admission::whereDate(

                'paid_at',

                today()

            )
            ->count();

        return $feeCount +
               $admissionCount;
    }

    public function countMonthlyCollections(
        $month = null,
        $year = null
    ) {

        $month =
            $month ?? now()->month;

        $year =
            $year ?? now()->year;

        $feeCount = FeeCollection::whereMonth(

                'payment_date',

                $month

            )
            ->whereYear(

                'payment_date',

                $year

            )
            ->count();

        $admissionCount = Admission::whereMonth(

                'paid_at',

                $month

            )
            ->whereYear(

                'paid_at',

                $year

            )
            ->count();

        return $feeCount +
               $admissionCount;
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED ADMISSIONS
    |--------------------------------------------------------------------------
    */

    public function getApprovedAdmissions()
    {
        return Admission::with([

                'studentClass:id,name'

            ])

            ->latest()

            ->get([

                'id',

                'name',

                'registration_no',

                'class_id',

                'total_fee',

                'paid_amount',

                'due_amount',

                'fee_due_date',

                'verification_status',

                'payment_type',

                'status'

            ]);
    }
}