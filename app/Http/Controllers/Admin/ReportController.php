<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Yajra\DataTables\Facades\DataTables;

use App\Models\User;

use App\Models\Charge;
use App\Models\WalletTransaction;

use App\Models\PanApplication;
use App\Models\PanCorrectionApplication;
use App\Models\PanWithoutDocument;
use App\Models\PanFindHistory;

use App\Models\AadhaarService;
use App\Models\CscService;
use App\Models\BankAccountService;
use App\Models\OtherService;
use App\Models\ItrFile;
use App\Models\tdsFile;
use App\Models\VoterIdService;
use App\Models\ProfitPartnerTransaction;

class ReportController extends Controller
{
    protected array $services = [

        'new-pan' => [
            'model' => PanApplication::class,
            'title' => 'New PAN Report',
            'customer' => 'applicant_name',
        ],

        'pan-correction' => [
            'model' => PanCorrectionApplication::class,
            'title' => 'PAN Correction Report',
            'customer' => 'applicant_name',
        ],

        'pan-without-document' => [
            'model' => PanWithoutDocument::class,
            'title' => 'PAN Without Document Report',
            'customer' => 'applicant_name',
        ],

        'pan-find' => [
            'model' => PanFindHistory::class,
            'title' => 'PAN Find Report',
            'customer' => 'applicant_name',
        ],

        'aadhaar' => [
            'model' => AadhaarService::class,
            'title' => 'Aadhaar Report',
            'customer' => 'customer_name',
            'dynamic' => true, // uses ->getField()
        ],

        'csc' => [
            'model' => CscService::class,
            'title' => 'CSC Report',
            'customer' => 'customer_name',
            'dynamic' => true,
        ],

        'bank' => [
            'model' => BankAccountService::class,
            'title' => 'Bank Report',
            'customer' => 'customer_name',
            'dynamic' => true,
        ],

        'voter' => [
            'model' => VoterIdService::class,
            'title' => 'Voter Report',
            'customer' => 'applicant_name',
            'dynamic' => true,
        ],

        'itr' => [
            'model' => ItrFile::class,
            'title' => 'ITR Report',
            'customer' => 'applicant_name',
        ],

         'tds' => [
            'model' => tdsFile::class,
            'title' => 'TDS Report',
            'customer' => 'applicant_name',
        ],

        'other' => [
            'model' => OtherService::class,
            'title' => 'Other Service Report',
            'customer' => 'customer_name',
            'dynamic' => true,
        ],

    ];

    
    protected array $fixedChargeCodes = [
        'new-pan' => 'new_pan_apply',
        'pan-correction' => 'pan_correction',
        'pan-find' => 'pan_find',
        'pan-without-document' => 'pan_without_document',
        'itr' => 'file_itr',
    ];

    public function index()
    {
        return view(
            'admin.reports.index',
            [
                'services' => $this->services,
            ]
        );
    }

    protected function getModel(string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        return $this->services[$service]['model'];
    }

    protected function getQuery(string $service)
    {
        $model = $this->getModel($service);

        return $model::query()
            ->with([
                'user.retailer.distributor',
                'assignedUser',
            ]);
    }

    protected function dateFilter($query, Request $request)
    {
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return $query;
    }

    protected function statusFilter($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    protected function paymentFilter($query, Request $request)
    {
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        return $query;
    }

    protected function executiveFilter($query, Request $request)
    {
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        return $query;
    }

    protected function retailerFilter($query, Request $request)
    {
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return $query;
    }

    
    protected function distributorFilter($query, Request $request)
    {
        if ($request->filled('distributor_id')) {
            $distributorId = $request->distributor_id;

            $query->whereHas('user.retailer', function ($q) use ($distributorId) {
                $q->where('distributor_id', $distributorId);
            });
        }

        return $query;
    }

    protected function applyFilters($query, Request $request)
    {
        $query = $this->dateFilter($query, $request);
        $query = $this->statusFilter($query, $request);
        $query = $this->paymentFilter($query, $request);
        $query = $this->executiveFilter($query, $request);
        $query = $this->retailerFilter($query, $request);
        $query = $this->distributorFilter($query, $request);

        return $query;
    }

    public function serviceReport(string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        return view(
            'admin.reports.service-report',
            [
                'service' => $service,
                'title' => $this->services[$service]['title'],
                'users' => User::role('Retailer')->get(),
                'executives' => User::role('Executive')->get(),
                'distributors' => User::role('Distributor')->get(),
            ]
        );
    }

    public function serviceData(Request $request, string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        $query = $this->getQuery($service);
        $query = $this->applyFilters($query, $request);

        return $this->datatable($query, $service);
    }

    protected function datatable($query, string $service)
    {
        $summaryQuery = clone $query;
        $summary = $this->summary($summaryQuery, $service);

        return DataTables::eloquent($query)
            ->addIndexColumn()   // <-- ADD THIS

            ->addColumn('customer', function ($row) use ($service) {
                return $this->customerName($row, $service);
            })
            ->addColumn('retailer', function ($row) {
                return optional($row->user)->name;
            })
            ->addColumn('distributor', function ($row) {
                return optional(
                    optional($row->user)->retailer
                )->distributor?->name;
            })
            ->addColumn('executive', function ($row) {
                return optional($row->assignedUser)->name;
            })
            ->addColumn('amount', function ($row) {
                return number_format(
                    $row->amount ?? $row->charge ?? 0,
                    2
                );
            })
            ->addColumn('status', function ($row) {
                return $row->status_badge;
            })
            ->addColumn('payment', function ($row) {
                return $row->payment_badge;
            })
            ->addColumn('application', function ($row) {
                return $row->application_no;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y h:i A');
            })
            ->addColumn('action', function ($row) use ($service) {
                return view('admin.reports.action', compact('row', 'service'));
            })
            ->rawColumns([
                'status',
                'payment',
                'action',
            ])
            ->with('summary', $summary)
            ->make(true);
    }

   
    protected function customerName($row, string $service)
    {
        if (!isset($this->services[$service])) {
            return '-';
        }

        $field = $this->services[$service]['customer'];
        $isDynamic = $this->services[$service]['dynamic'] ?? false;

        if ($isDynamic) {
            return $row->getField($field);
        }

        return $row->{$field} ?? '-';
    }

    protected function totals(Collection $rows)
    {
        return [
            'applications' => $rows->count(),
            'amount' => $rows->sum(function ($r) {
                return $r->amount ?? $r->charge ?? 0;
            }),
            'approved' => $rows->where('status', 'Approved')->count(),
            'pending' => $rows->where('status', 'Pending')->count(),
            'processing' => $rows->where('status', 'Processing')->count(),
            'rejected' => $rows->where('status', 'Rejected')->count(),
        ];
    }

   
    protected function summary($query, string $service)
    {
        $rows = $query->get();

        $totalAmount = 0;
        $executiveCommission = 0;
        $distributorCommission = 0;

        /*
        |--------------------------------------------------------------------------
        | Resolve Charge Codes
        |--------------------------------------------------------------------------
        */

        $chargeCodesByRow = $rows->map(function ($row) use ($service) {

            return $this->resolveChargeCode($row, $service);

        });

        $distinctCodes = $chargeCodesByRow
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Load Charges With Commissions
        |--------------------------------------------------------------------------
        */

        $charges = Charge::with('commissions')
            ->whereIn('code', $distinctCodes)
            ->get()
            ->keyBy('code');

        /*
        |--------------------------------------------------------------------------
        | Calculate Revenue & Commissions
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $index => $row) {

            $amount = $row->amount ?? $row->charge ?? 0;

            $totalAmount += $amount;

            $chargeCode = $chargeCodesByRow[$index];

            if (!$chargeCode || !$charges->has($chargeCode)) {
                continue;
            }

            $charge = $charges->get($chargeCode);

            $executiveCommission += (float) optional(

                $charge->commissions
                    ->where('role', 'Executive')
                    ->first()

            )->value;

            $distributorCommission += (float) optional(

                $charge->commissions
                    ->where('role', 'Distributor')
                    ->first()

            )->value;
        }

        /*
        |--------------------------------------------------------------------------
        | Net Business Profit
        |--------------------------------------------------------------------------
        */

        $netProfit =

            $totalAmount
            - $executiveCommission
            - $distributorCommission;

        /*
        |--------------------------------------------------------------------------
        | Partner Profit (Only Current Filtered Services)
        |--------------------------------------------------------------------------
        */

        $serviceIds = $rows
            ->pluck('id')
            ->toArray();

        $partnerProfit = 0;

        if (!empty($serviceIds)) {

            $partnerProfit = ProfitPartnerTransaction::query()

                ->where('service_type', $service)

                ->whereIn('service_id', $serviceIds)

                ->sum('profit_amount');

        }


       /*
        |--------------------------------------------------------------------------
        | Company Profit
        |--------------------------------------------------------------------------
        */

        $companyProfit = $netProfit - $partnerProfit;

        /*
        |--------------------------------------------------------------------------
        | Return Summary
        |--------------------------------------------------------------------------
        */

        return [

            'applications' => $rows->count(),

            'amount' => round($totalAmount, 2),

            'approved' => $rows->where('status', 'Approved')->count(),

            'pending' => $rows->where('status', 'Pending')->count(),

            'processing' => $rows->where('status', 'Processing')->count(),

            'rejected' => $rows->where('status', 'Rejected')->count(),

            'executive_commission' => round($executiveCommission, 2),

            'distributor_commission' => round($distributorCommission, 2),

            'net_profit' => round($netProfit, 2),

            'partner_profit' => round($partnerProfit, 2),

            'company_profit' => round($companyProfit, 2),

        ];
    }
    protected function resolveChargeCode($row, string $service): ?string
    {
        if (isset($this->fixedChargeCodes[$service])) {
            return $this->fixedChargeCodes[$service];
        }

        // aadhaar, bank, csc, other, voter — derived from the row's own service_slug
        if (isset($row->service_slug)) {
            return str_replace('-', '_', $row->service_slug);
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | View Details
    |--------------------------------------------------------------------------
    */

    public function show(string $service, $id)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        $row = $this->getQuery($service)
            ->findOrFail($id);

        return view(
            'admin.reports.show',
            [
                'service' => $service,
                'title' => $this->services[$service]['title'],
                'row' => $row,
                'customer' => $this->customerName($row, $service),
            ]
        );
    }

    

    public function exportExcel(Request $request, string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        $query = $this->getQuery($service);
        $query = $this->applyFilters($query, $request);
        $rows = $query->get();

        $filename = $service.'-report-'.now()->format('Y-m-d-His').'.csv';

    
        return response()->streamDownload(function () use ($rows, $service) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Application', 'Customer', 'Retailer', 'Distributor',
                'Executive', 'Amount', 'Payment', 'Status', 'Date',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->application_no,
                    $this->customerName($row, $service),
                    optional($row->user)->name,
                    optional(optional($row->user)->retailer)->distributor?->name,
                    optional($row->assignedUser)->name,
                    $row->amount ?? $row->charge ?? 0,
                    $row->payment_status,
                    $row->status,
                    $row->created_at->format('d-m-Y h:i A'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportPdf(Request $request, string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        $query = $this->getQuery($service);
        $query = $this->applyFilters($query, $request);
        $rows = $query->get();

        $data = [
            'service' => $service,
            'title' => $this->services[$service]['title'],
            'rows' => $rows,
            'summary' => $this->totals($rows),
        ];

        return \Pdf::loadView('admin.reports.pdf', $data)
            ->download($service.'-report-'.now()->format('Y-m-d-His').'.pdf');
    }

    public function printReport(Request $request, string $service)
    {
        abort_if(
            !isset($this->services[$service]),
            404
        );

        $query = $this->getQuery($service);
        $query = $this->applyFilters($query, $request);
        $rows = $query->get();

        return view(
            'admin.reports.print',
            [
                'service' => $service,
                'title' => $this->services[$service]['title'],
                'rows' => $rows,
                'summary' => $this->totals($rows),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ledger Reports
    |--------------------------------------------------------------------------
    */

    public function walletLedger(Request $request)
    {
        $query = WalletTransaction::query()
            ->with('user')
            ->latest();

        $query = $this->dateFilter($query, $request);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return view(
            'admin.reports.wallet-ledger',
            [
                'transactions' => $query->paginate(10)->withQueryString(),
                'users' => User::role('Retailer')->get(),
            ]
        );
    }

    public function commissionLedger(Request $request)
    {
       
        $query = WalletTransaction::query()
            ->with('user')
            ->where('type', 'commission')
            ->latest();

        $query = $this->dateFilter($query, $request);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return view(
            'admin.reports.commission-ledger',
            [
                'transactions' => $query->paginate(10)->withQueryString(),
                'users' => User::query()->get(),
            ]
        );
    }


    public function revenueReport(Request $request)
    {
        $breakdown = [];
        $grandTotal = 0;

        foreach (array_keys($this->services) as $service) {
            $query = $this->getQuery($service);
            $query = $this->applyFilters($query, $request);

            $summary = $this->summary($query, $service);

            $breakdown[$service] = $summary;
            $grandTotal += $summary['amount'];
        }

        return view(
            'admin.reports.revenue',
            [
                'breakdown' => $breakdown,
                'grand_total' => $grandTotal,
            ]
        );
    }

    public function profitLoss(Request $request)
    {
        $breakdown = [];

        $serviceIds = [];

        $totals = [

            'amount' => 0,

            'executive_commission' => 0,

            'distributor_commission' => 0,

            'net_profit' => 0,

            'partner_profit' => 0,

            'company_profit' => 0,

        ];

        /*
        |--------------------------------------------------------------------------
        | Service Wise Summary
        |--------------------------------------------------------------------------
        */

        foreach (array_keys($this->services) as $service) {

            $query = $this->getQuery($service);

            $query = $this->applyFilters($query, $request);

            /*
            |--------------------------------------------------------------------------
            | Store Filtered Service IDs
            |--------------------------------------------------------------------------
            */

            $ids = (clone $query)
                ->pluck('id')
                ->toArray();

            $serviceIds = array_merge(
                $serviceIds,
                $ids
            );

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $summary = $this->summary($query, $service);

            $breakdown[$service] = $summary;

            $totals['amount'] += $summary['amount'];

            $totals['executive_commission'] += $summary['executive_commission'];

            $totals['distributor_commission'] += $summary['distributor_commission'];

            $totals['net_profit'] += $summary['net_profit'];

            $totals['partner_profit'] += $summary['partner_profit'];

            $totals['company_profit'] += $summary['company_profit'];
        }

        /*
        |--------------------------------------------------------------------------
        | Remove Duplicate IDs
        |--------------------------------------------------------------------------
        */

        $serviceIds = array_unique($serviceIds);

        /*
        |--------------------------------------------------------------------------
        | Round Totals
        |--------------------------------------------------------------------------
        */

        foreach ($totals as $key => $value) {

            $totals[$key] = round($value, 2);

        }

        /*
        |--------------------------------------------------------------------------
        | Partner Distribution (Only Filtered Services)
        |--------------------------------------------------------------------------
        */

        $partnerDistribution = ProfitPartnerTransaction::query()

            ->selectRaw("
                partner_id,
                profit_percentage,
                SUM(profit_amount) as total_profit
            ")

            ->with('partner');

        if (!empty($serviceIds)) {

            $partnerDistribution->whereIn(
                'service_id',
                $serviceIds
            );

        } else {

            $partnerDistribution->whereRaw('1 = 0');

        }

        $partnerDistribution = $partnerDistribution

            ->groupBy(
                'partner_id',
                'profit_percentage'
            )

            ->orderByDesc('total_profit')

            ->get();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.reports.profit-loss',
            compact(
                'breakdown',
                'totals',
                'partnerDistribution'
            )
        );
    }
}
