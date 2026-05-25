<?php

namespace App\Services;

use App\DTO\FeeCollectionDTO;

use App\Models\Admission;

use App\Repositories\FeeCollectionRepository;

use Illuminate\Support\Facades\DB;

class FeeCollectionService
{
    public function __construct(
        protected FeeCollectionRepository $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | GET ALL COLLECTIONS
    |--------------------------------------------------------------------------
    */

    public function getAll($perPage = 20)
    {
        return $this->repo->getAll(
            $perPage
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FIND COLLECTION
    |--------------------------------------------------------------------------
    */

    public function findById(int $id)
    {
        return $this->repo->findById(
            $id
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function store(
        FeeCollectionDTO $dto
    ) {

        return DB::transaction(
            function () use ($dto) {

                /*
                |--------------------------------------------------------------------------
                | CREATE COLLECTION
                |--------------------------------------------------------------------------
                */

                $collection =
                    $this->repo->store(

                        $dto->toArray()

                    );

                /*
                |--------------------------------------------------------------------------
                | UPDATE ADMISSION SUMMARY
                |--------------------------------------------------------------------------
                */

                $this->updateAdmissionFeeSummary(

                    $collection->admission_id,

                    $dto->fee_due_date ?? null

                );

                return $collection;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        FeeCollectionDTO $dto
    ) {

        return DB::transaction(
            function () use (
                $id,
                $dto
            ) {

                /*
                |--------------------------------------------------------------------------
                | UPDATE COLLECTION
                |--------------------------------------------------------------------------
                */

                $collection =
                    $this->repo->update(

                        $id,

                        $dto->toArray()

                    );

                /*
                |--------------------------------------------------------------------------
                | UPDATE SUMMARY
                |--------------------------------------------------------------------------
                */

                $this->updateAdmissionFeeSummary(

                    $collection->admission_id,

                    $dto->fee_due_date ?? null

                );

                return $collection;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function delete(int $id)
    {
        return DB::transaction(
            function () use ($id) {

                $collection =
                    $this->repo->findById($id);

                $admissionId =
                    $collection->admission_id;

                /*
                |--------------------------------------------------------------------------
                | DELETE
                |--------------------------------------------------------------------------
                */

                $this->repo->delete($id);

                /*
                |--------------------------------------------------------------------------
                | UPDATE SUMMARY AGAIN
                |--------------------------------------------------------------------------
                */

                $this->updateAdmissionFeeSummary(
                    $admissionId
                );

                return true;
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ADMISSION SUMMARY
    |--------------------------------------------------------------------------
    */

    protected function updateAdmissionFeeSummary(
        int $admissionId,
        $feeDueDate = null
    ) {

        $admission =
            Admission::findOrFail(
                $admissionId
            );

        /*
        |--------------------------------------------------------------------------
        | TOTAL PAID
        |--------------------------------------------------------------------------
        */

        $totalPaid =
            $this->repo
                ->getAdmissionHistory(
                    $admissionId
                )
                ->whereIn(
                    'status',
                    [

                        'paid',

                        'partial'

                    ]
                )
                ->sum(
                    'paid_amount'
                );

        /*
        |--------------------------------------------------------------------------
        | TOTAL FEE
        |--------------------------------------------------------------------------
        */

        $totalFee =
            $admission->total_fee;

        /*
        |--------------------------------------------------------------------------
        | DUE
        |--------------------------------------------------------------------------
        */

        $due =
            max(
                $totalFee - $totalPaid,
                0
            );

        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        if($due <= 0){

            $status =
                Admission::STATUS_PAID;

        }elseif($totalPaid > 0){

            $status =
                Admission::STATUS_PARTIAL_PAID;

        }else{

            $status =
                Admission::STATUS_PAYMENT_PENDING;
        }

        /*
        |--------------------------------------------------------------------------
        | OVERDUE CHECK
        |--------------------------------------------------------------------------
        */

        if(

            $due > 0

            &&

            !empty(
                $admission->fee_due_date
            )

            &&

            now()->gt(
                $admission->fee_due_date
            )

        ){

            $status =
                'overdue';
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $updateData = [

            'paid_amount' =>

                $totalPaid,

            'due_amount' =>

                $due,

            'status' =>

                $status
        ];

        /*
        |--------------------------------------------------------------------------
        | PARTIAL PAYMENT
        |--------------------------------------------------------------------------
        */

        if($due > 0){

            /*
            |--------------------------------------------------------------------------
            | ADMIN CONTROLLED DUE DATE
            |--------------------------------------------------------------------------
            */

            if(!empty($feeDueDate)){

                $updateData[
                    'fee_due_date'
                ] = $feeDueDate;
            }

        }

        /*
        |--------------------------------------------------------------------------
        | FULLY PAID
        |--------------------------------------------------------------------------
        */

        else{

            $updateData[
                'fee_due_date'
            ] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE ADMISSION
        |--------------------------------------------------------------------------
        */

        $admission->update(
            $updateData
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMISSION HISTORY
    |--------------------------------------------------------------------------
    */

    public function getAdmissionHistory(
        int $admissionId
    ) {

        return $this->repo
            ->getAdmissionHistory(
                $admissionId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RECENT COLLECTIONS
    |--------------------------------------------------------------------------
    */

    public function getRecentCollections(
        int $limit = 10
    ) {

        return $this->repo
            ->getRecentCollections(
                $limit
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TODAY COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getTodayCollection()
    {
        return $this->repo
            ->getTodayCollection();
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

        return $this->repo
            ->getMonthlyCollection(
                $month,
                $year
            );
    }

    /*
    |--------------------------------------------------------------------------
    | YEARLY COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getYearlyCollection(
        $year = null
    ) {

        return $this->repo
            ->getYearlyCollection(
                $year
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getTotalCollection()
    {
        return $this->repo
            ->getTotalCollection();
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS WISE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getClassWiseCollection(
        int $classId
    ) {

        return $this->repo
            ->getClassWiseCollection(
                $classId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT MODE COLLECTION
    |--------------------------------------------------------------------------
    */

    public function getPaymentModeCollection(
        string $method
    ) {

        return $this->repo
            ->getPaymentModeCollection(
                $method
            );
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

        return $this->repo
            ->getDateRangeCollection(
                $from,
                $to
            );
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

        return $this->repo
            ->getDateRangeHistory(
                $from,
                $to
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RECEIPT SEARCH
    |--------------------------------------------------------------------------
    */

    public function findByReceiptNo(
        string $receiptNo
    ) {

        return $this->repo
            ->findByReceiptNo(
                $receiptNo
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TRANSACTION SEARCH
    |--------------------------------------------------------------------------
    */

    public function findByTransactionId(
        string $transactionId
    ) {

        return $this->repo
            ->findByTransactionId(
                $transactionId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTOR HISTORY
    |--------------------------------------------------------------------------
    */

    public function getCollectorHistory(
        int $userId
    ) {

        return $this->repo
            ->getCollectorHistory(
                $userId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | FAILED PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function getFailedPayments()
    {
        return $this->repo
            ->getFailedPayments();
    }

    /*
    |--------------------------------------------------------------------------
    | REFUNDED PAYMENTS
    |--------------------------------------------------------------------------
    */

    public function getRefundedPayments()
    {
        return $this->repo
            ->getRefundedPayments();
    }

    /*
    |--------------------------------------------------------------------------
    | COUNTS
    |--------------------------------------------------------------------------
    */

    public function countTodayCollections()
    {
        return $this->repo
            ->countTodayCollections();
    }

    public function countMonthlyCollections(
        $month = null,
        $year = null
    ) {

        return $this->repo
            ->countMonthlyCollections(
                $month,
                $year
            );
    }

    /*
    |--------------------------------------------------------------------------
    | OVERDUE ADMISSIONS
    |--------------------------------------------------------------------------
    */

    public function getOverdueAdmissions()
    {
        return Admission::where(

                'due_amount',

                '>', 0

            )
            ->whereNotNull(
                'fee_due_date'
            )
            ->whereDate(

                'fee_due_date',

                '<',

                today()

            )
            ->latest()
            ->get();
    }
}