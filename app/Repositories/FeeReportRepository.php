<?php

namespace App\Repositories;

use App\Models\FeeCollection;
use App\Models\Admission;

use Illuminate\Support\Collection;

use Carbon\Carbon;

class FeeReportRepository
{
    /*
    |--------------------------------------------------------------------------
    | MAIN ERP REPORT
    |--------------------------------------------------------------------------
    */

    public function getReport(
        array $filters = []
    ) {

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
            ->get([
                'id',
                'name',
                'registration_no',
                'class_id',
                'paid_amount',
                'payment_type',
                'payment_id',
                'paid_at',
                'verification_status',
                'due_amount',
                'fee_due_date'
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

                    'class' =>

                        optional(
                            $item->studentClass
                        )->name,

                    'class_id' =>

                        $item->class_id,

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

                    'due_amount' =>

                        $item->due_amount,

                    'fee_due_date' =>

                        $item->fee_due_date
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | FEE COLLECTIONS
        |--------------------------------------------------------------------------
        */

        $collections = FeeCollection::with([

                'admission:id,name,class_id',

                'admission.studentClass:id,name'

            ])
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

                    'class' =>

                        optional(
                            optional(
                                $item->admission
                            )->studentClass
                        )->name,

                    'class_id' =>

                        optional(
                            $item->admission
                        )->class_id,

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

                    'due_amount' => 0,

                    'fee_due_date' => null
                ];
            });

        /*
        |--------------------------------------------------------------------------
        | MERGE ERP LEDGER
        |--------------------------------------------------------------------------
        */

        $data = $admissions
            ->merge($collections);

        /*
        |--------------------------------------------------------------------------
        | CLASS FILTER
        |--------------------------------------------------------------------------
        */

        if(!empty($filters['class_id'])){

            $data = $data->where(

                'class_id',

                $filters['class_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if(!empty($filters['status'])){

            $data = $data->filter(function($item) use ($filters){

                /*
                |--------------------------------------------------------------------------
                | OVERDUE
                |--------------------------------------------------------------------------
                */

                if(

                    $filters['status']
                    ===
                    'overdue'

                ){

                    return

                        $item['due_amount'] > 0

                        &&

                        !empty(
                            $item['fee_due_date']
                        )

                        &&

                        Carbon::parse(

                            $item['fee_due_date']

                        )->isPast();
                }

                return strtolower(

                    $item['status']

                ) === strtolower(

                    $filters['status']
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | METHOD FILTER
        |--------------------------------------------------------------------------
        */

        if(!empty($filters['method'])){

            $data = $data->where(

                'method',

                $filters['method']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if(!empty($filters['from_date'])){

            $data = $data->filter(function($item) use ($filters){

                return Carbon::parse(

                    $item['date']

                )->gte(

                    Carbon::parse(
                        $filters['from_date']
                    )
                );
            });
        }

        if(!empty($filters['to_date'])){

            $data = $data->filter(function($item) use ($filters){

                return Carbon::parse(

                    $item['date']

                )->lte(

                    Carbon::parse(
                        $filters['to_date']
                    )->endOfDay()
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */

        return $data
            ->sortByDesc('date')
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING DUES
    |--------------------------------------------------------------------------
    */

    public function getPendingDues(
        array $filters = []
    ) {

        return Admission::sum(
            'due_amount'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PARTIAL PAYMENTS COUNT
    |--------------------------------------------------------------------------
    */

    public function getPartialPaymentsCount(
        array $filters = []
    ) {

        return Admission::where(

                'due_amount',

                '>', 0

            )
            ->where(

                'paid_amount',

                '>', 0

            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | OVERDUE STUDENTS COUNT
    |--------------------------------------------------------------------------
    */

    public function getOverdueStudentsCount(
        array $filters = []
    ) {

        return Admission::where(

                'due_amount',

                '>', 0

            )
            ->whereDate(

                'fee_due_date',

                '<',

                now()

            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | OVERDUE REPORT
    |--------------------------------------------------------------------------
    */

    public function getOverdueReport()
    {
        return Admission::with([

                'studentClass:id,name'

            ])
            ->where(

                'due_amount',

                '>', 0

            )
            ->whereDate(

                'fee_due_date',

                '<',

                now()

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PARTIAL PAYMENT REPORT
    |--------------------------------------------------------------------------
    */

    public function getPartialPaymentReport()
    {
        return Admission::with([

                'studentClass:id,name'

            ])
            ->where(

                'due_amount',

                '>', 0

            )
            ->where(

                'paid_amount',

                '>', 0

            )
            ->latest()
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD REPORT
    |--------------------------------------------------------------------------
    */

    public function getPaymentMethodReport()
    {
        return collect([

            [

                'method' => 'Cash',

                'total' => FeeCollection::where(

                    'payment_method',

                    'Cash'

                )->sum('paid_amount')
            ],

            [

                'method' => 'UPI',

                'total' => FeeCollection::where(

                    'payment_method',

                    'UPI'

                )->sum('paid_amount')
            ],

            [

                'method' => 'Card',

                'total' => FeeCollection::where(

                    'payment_method',

                    'Card'

                )->sum('paid_amount')
            ],

            [

                'method' => 'Online',

                'total' => FeeCollection::where(

                    'payment_method',

                    'Online'

                )->sum('paid_amount')
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DAILY REPORT
    |--------------------------------------------------------------------------
    */

    public function getDailyReport(
        $date
    ) {

        return $this->getReport([
            'from_date' => $date,
            'to_date' => $date
        ]);
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

        $from =
            Carbon::create(
                $year,
                $month,
                1
            )->startOfMonth();

        $to =
            Carbon::create(
                $year,
                $month,
                1
            )->endOfMonth();

        return $this->getReport([

            'from_date' =>

                $from->toDateString(),

            'to_date' =>

                $to->toDateString()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | YEARLY REPORT
    |--------------------------------------------------------------------------
    */

    public function getYearlyReport(
        $year
    ) {

        return $this->getReport([

            'from_date' =>

                Carbon::create(
                    $year,
                    1,
                    1
                )->startOfYear()
                 ->toDateString(),

            'to_date' =>

                Carbon::create(
                    $year,
                    12,
                    31
                )->endOfYear()
                 ->toDateString()
        ]);
    }
}