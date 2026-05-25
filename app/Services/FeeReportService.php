<?php

namespace App\Services;

use App\Repositories\FeeReportRepository;

class FeeReportService
{
    public function __construct(
        protected FeeReportRepository $repo
    ) {}

    /*
    |--------------------------------------------------------------------------
    | MAIN REPORT
    |--------------------------------------------------------------------------
    */

    public function getReport(
        array $filters = []
    ) {

        return $this->repo
            ->getReport(
                $filters
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY CARDS
    |--------------------------------------------------------------------------
    */

    public function getSummary(
        array $filters = []
    ) {

        $data =
            $this->repo
                ->getReport(
                    $filters
                );

        return [

            /*
            |--------------------------------------------------------------------------
            | TOTAL COLLECTION
            |--------------------------------------------------------------------------
            */

            'total_collection' =>

                $data->sum(
                    'amount'
                ),

            /*
            |--------------------------------------------------------------------------
            | TOTAL TRANSACTIONS
            |--------------------------------------------------------------------------
            */

            'total_transactions' =>

                $data->count(),

            /*
            |--------------------------------------------------------------------------
            | PENDING DUES
            |--------------------------------------------------------------------------
            */

            'pending_dues' =>

                $this->repo
                    ->getPendingDues(
                        $filters
                    ),

            /*
            |--------------------------------------------------------------------------
            | PARTIAL PAYMENTS
            |--------------------------------------------------------------------------
            */

            'partial_payments' =>

                $this->repo
                    ->getPartialPaymentsCount(
                        $filters
                    ),

            /*
            |--------------------------------------------------------------------------
            | OVERDUE STUDENTS
            |--------------------------------------------------------------------------
            */

            'overdue_students' =>

                $this->repo
                    ->getOverdueStudentsCount(
                        $filters
                    )
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | OVERDUE REPORT
    |--------------------------------------------------------------------------
    */

    public function getOverdueReport()
    {
        return $this->repo
            ->getOverdueReport();
    }

    /*
    |--------------------------------------------------------------------------
    | PARTIAL PAYMENT REPORT
    |--------------------------------------------------------------------------
    */

    public function getPartialPaymentReport()
    {
        return $this->repo
            ->getPartialPaymentReport();
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD REPORT
    |--------------------------------------------------------------------------
    */

    public function getPaymentMethodReport()
    {
        return $this->repo
            ->getPaymentMethodReport();
    }

    /*
    |--------------------------------------------------------------------------
    | DAILY REPORT
    |--------------------------------------------------------------------------
    */

    public function getDailyReport(
        $date
    ) {

        return $this->repo
            ->getDailyReport(
                $date
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MONTHLY REPORT
    |--------------------------------------------------------------------------
    */

    public function getMonthlyReport(
        $month,
        $year
    ) {

        return $this->repo
            ->getMonthlyReport(

                $month,

                $year
            );
    }

    /*
    |--------------------------------------------------------------------------
    | YEARLY REPORT
    |--------------------------------------------------------------------------
    */

    public function getYearlyReport(
        $year
    ) {

        return $this->repo
            ->getYearlyReport(
                $year
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS WISE REPORT
    |--------------------------------------------------------------------------
    */

    public function getClassWiseReport(
        $classId
    ) {

        return $this->repo
            ->getClassWiseReport(
                $classId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT LEDGER
    |--------------------------------------------------------------------------
    */

    public function getStudentLedger(
        $admissionId
    ) {

        return $this->repo
            ->getStudentLedger(
                $admissionId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT REPORT
    |--------------------------------------------------------------------------
    */

    public function exportReport(
        array $filters = []
    ) {

        return $this->repo
            ->getReport(
                $filters
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TODAY SUMMARY
    |--------------------------------------------------------------------------
    */

    public function getTodaySummary()
    {
        return [

            'collection' =>

                $this->repo
                    ->getTodayCollection(),

            'transactions' =>

                $this->repo
                    ->getTodayTransactions(),

            'partial_payments' =>

                $this->repo
                    ->getTodayPartialPayments(),

            'overdue_students' =>

                $this->repo
                    ->getTodayOverdueStudents()
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MONTH SUMMARY
    |--------------------------------------------------------------------------
    */

    public function getMonthSummary(
        $month = null,
        $year = null
    ) {

        return [

            'collection' =>

                $this->repo
                    ->getMonthCollection(

                        $month,

                        $year
                    ),

            'transactions' =>

                $this->repo
                    ->getMonthTransactions(

                        $month,

                        $year
                    ),

            'partial_payments' =>

                $this->repo
                    ->getMonthPartialPayments(

                        $month,

                        $year
                    ),

            'overdue_students' =>

                $this->repo
                    ->getMonthOverdueStudents(

                        $month,

                        $year
                    )
        ];
    }
}