<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReservationService;
use App\Http\Requests\ReservationRequest;
use App\DTO\ReservationDTO;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $service;

    public function __construct(ReservationService $service)
    {
        $this->service = $service;
    }

    /* =========================
       LIST PAGE
    ========================= */
    public function index()
    {
        return view('admin.reservations.index');
    }

    /* =========================
       DATATABLE LIST (AJAX)
    ========================= */
    public function list()
    {
        return response()->json([
            'data' => $this->service->getReservationListAdmin()
        ]);
    }

    /* =========================
       STORE (FORM → DTO → SERVICE)
    ========================= */
    public function store(ReservationRequest $request)
    {
        $dto = ReservationDTO::fromRequest($request);

        $this->service->create($dto);

        return response()->json([
            'success' => true,
            'message' => 'Reservation booked successfully'
        ]);
    }

    /* =========================
       UPDATE STATUS
    ========================= */
    public function updateStatus(ReservationRequest $request, $id)
    {
        $dto = ReservationDTO::fromRequest($request);

        $this->service->updateStatus($id, $dto->status);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /* =========================
       DELETE
    ========================= */
    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Reservation deleted successfully'
        ]);
    }
}