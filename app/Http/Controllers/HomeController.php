<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use App\Services\PageService;
use App\Services\MenuService;
use App\Services\OrderService;
use App\Services\ReservationService;
use App\Services\FooterService; // ✅ ADD

use App\Models\Reservation;
use App\DTO\ReservationDTO;
use App\Models\PopupOffer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected HomeService $homeService;
    protected PageService $pageService;
    protected MenuService $menuService;
    protected OrderService $orderService;
    protected ReservationService $reservationService;
    protected FooterService $footerService; // ✅ ADD

    public function __construct(
        HomeService $homeService,
        PageService $pageService,
        MenuService $menuService,
        OrderService $orderService,
        ReservationService $reservationService,
        FooterService $footerService // ✅ ADD
    ) {
        $this->homeService = $homeService;
        $this->pageService = $pageService;
        $this->menuService = $menuService;
        $this->orderService = $orderService;
        $this->reservationService = $reservationService;
        $this->footerService = $footerService; // ✅ ADD
    }

    /* =========================
       COMMON FOOTER DATA
    ========================= */
    private function footer()
    {
        return $this->footerService->getFooterData();
    }

    /* =========================
       HOME PAGE
    ========================= */
    public function index()
    {
        $data = $this->homeService->getHomeData();

        $popup = PopupOffer::where('is_active', 1)
            ->where(fn($q) => $q->whereNull('start_at')->orWhere('start_at','<=',now()))
            ->where(fn($q) => $q->whereNull('end_at')->orWhere('end_at','>=',now()))
            ->latest()
            ->first();

        $data['popup'] = $popup;

        return view('home', array_merge($data, $this->footer())); // ✅ ADD
    }

    /* =========================
       MENU PAGE
    ========================= */
    public function menu()
    {
        $categories = $this->menuService->getMenuData();

        return view('menu', array_merge(
            compact('categories'),
            $this->footer()
        ));
    }

    public function menuDetail($id)
    {
        $product = $this->menuService->getMenuById($id);
        $related = $this->menuService->getRelatedMenus($id);

        return view('menu-details', array_merge(
            compact('product','related'),
            $this->footer()
        ));
    }

    /* =========================
       ORDERS PAGE
    ========================= */
    public function orders()
    {
        $orders = $this->orderService->getUserOrders();

        return view('order', array_merge(
            compact('orders'),
            $this->footer()
        ));
    }

    /* =========================
       TRACK PAGE
    ========================= */
    public function track()
    {
        return view('track', $this->footer());
    }

    public function trackOrder(Request $request)
    {
        $request->validate(['order_id' => 'required']);

        $order = $this->orderService->trackOrder($request->order_id);

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        return view('track', array_merge(
            compact('order'),
            $this->footer()
        ));
    }

    /* =========================
       RESERVATION PAGE
    ========================= */
    public function reservation()
    {
        return view('reservation', $this->footer());
    }

    /* =========================
       STORE RESERVATION
    ========================= */
    public function storeReserveTable(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:100',
            'email'  => 'required|email',
            'phone'  => 'required',
            'date'   => 'required|date|after_or_equal:today',
            'time'   => 'required',
            'guests' => 'required|integer|min:1|max:20',
            'notes'  => 'nullable|string'
        ]);

        try {

            return DB::transaction(function () use ($request) {

                $dto = ReservationDTO::fromRequest($request);
                $reservation = $this->reservationService->store($dto);

                return response()->json([
                    'success' => true,
                    'message' => 'Table booked successfully 🎉',
                    'redirect' => route('customer.dashboard'),
                    'data' => [
                        'id'    => $reservation->id,
                        'table' => $reservation->table->name ?? null,
                        'date'  => $reservation->date,
                        'time'  => $reservation->time
                    ]
                ]);
            });

        } catch (\Throwable $e) {

            Log::error('Reservation Failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Something went wrong'
            ], 422);
        }
    }

    /* =========================
       AVAILABLE SLOTS
    ========================= */
    public function availableSlots(Request $request)
    {
        $date = $request->date;

        $slots = ['10:00','11:00','12:00','13:00','14:00','15:00','18:00','19:00','20:00','21:00','22:00'];

        $available = [];

        foreach ($slots as $slot) {

            $totalCapacity = \DB::table('tables')
                ->where('is_active', 1)
                ->sum('capacity');

            $bookedSeats = Reservation::where('date', $date)
                ->where('time', $slot)
                ->where('status', '!=', 'cancelled')
                ->sum('guests');

            $remaining = $totalCapacity - $bookedSeats;

            $available[] = [
                'time' => $slot,
                'available' => $remaining > 0,
                'remaining' => max($remaining, 0)
            ];
        }

        return response()->json($available);
    }

    /* =========================
       CMS PAGE
    ========================= */
    public function page(string $slug)
    {
        $page = $this->pageService->getBySlug($slug);

        return view('page', array_merge(
            compact('page'),
            $this->footer() // ✅ ADD
        ));
    }
}