<?php

namespace App\Services;

use App\Repositories\ReservationRepository;
use App\DTO\ReservationDTO;
use Carbon\Carbon;

class ReservationService
{
    protected $repo;

    public function __construct(ReservationRepository $repo)
    {
        $this->repo = $repo;
    }

    /* =========================
       CONFIG (CENTRALIZED)
    ========================= */
    private function allowedSlots()
    {
        return config('reservation.slots', [
            '10:00','11:00','12:00','13:00',
            '14:00','15:00','18:00','19:00',
            '20:00','21:00','22:00'
        ]);
    }

    private function capacity()
    {
        return config('reservation.capacity', 50);
    }

    /* =========================
       STORE (MAIN LOGIC 🔥)
    ========================= */
    public function store(ReservationDTO $dto)
    {
        $allowedSlots = $this->allowedSlots();

        $time = Carbon::parse($dto->time)->format('H:i');

        if (!in_array($time, $allowedSlots)) {
            throw new \Exception('Invalid time slot selected');
        }

        $now = Carbon::now();

        if (
            $dto->date === $now->toDateString() &&
            Carbon::parse($time)->lt($now)
        ) {
            throw new \Exception('Cannot book past time slot');
        }

        $exists = $this->repo->checkDuplicateBooking(
            $dto->email,
            $dto->date,
            $time
        );

        if ($exists) {
            throw new \Exception('You already booked this slot');
        }

        $tables = $this->repo->getAvailableTables(
            $dto->date,
            $time,
            $dto->guests
        );

        if ($tables->isEmpty()) {
            throw new \Exception('No table available for selected slot');
        }

       
        $table = $tables->first();

        // 6️⃣ GLOBAL CAPACITY CHECK
        $bookedSeats = $this->repo->getBookedSeats(
            $dto->date,
            $time
        );

        $capacity = $this->capacity();

        if (($bookedSeats + $dto->guests) > $capacity) {
            throw new \Exception(
                "Only " . ($capacity - $bookedSeats) . " seats available"
            );
        }

        return $this->repo->create([
            ...$dto->toArray(),
            'time' => $time, 
            'table_id' => $table->id
        ]);
    }
    /* =========================
       LIST
    ========================= */
    public function list()
    {
        return $this->repo->getAll();
    }

    /* =========================
       UPDATE STATUS
    ========================= */
    public function updateStatus($id, $status)
    {
        $validStatuses = ['pending','confirmed','cancelled'];

        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid reservation status');
        }

        return $this->repo->updateStatus($id, $status);
    }

    /* =========================
       DATATABLE
    ========================= */
    public function getReservationList($userId)
    {
        return $this->repo->getByUser($userId)->map(function ($r) {

            return [
                'id'     => $r->id,
                'name'   => $r->name,
                'email'  => $r->email,
                'phone'  => $r->phone,
                'date'   => $r->date,
                'time'   => $r->time,
                'guests' => $r->guests,
                'table'  => $r->table->name ?? 'N/A',
                'status' => $r->status,
                'actions' => '
                    <button class="btn btn-success btn-sm updateStatus" data-id="'.$r->id.'" data-status="confirmed">✔</button>
                    <button class="btn btn-danger btn-sm updateStatus" data-id="'.$r->id.'" data-status="cancelled">✖</button>
                '
            ];
        });
    }
    

    public function getReservationListAdmin()
    {
        return $this->repo->getAll()->map(function ($r) {

            return [
                'id'     => $r->id,
                'name'   => $r->name,
                'email'  => $r->email,
                'phone'  => $r->phone,
                'date'   => $r->date,
                'time'   => $r->time,
                'guests' => $r->guests,
                'table'  => $r->table->name ?? 'N/A',
                'status' => $r->status,
                'actions' => '
                    <button class="btn btn-success btn-sm updateStatus" data-id="'.$r->id.'" data-status="confirmed">✔</button>
                    <button class="btn btn-danger btn-sm updateStatus" data-id="'.$r->id.'" data-status="cancelled">✖</button>
                '
            ];
        });
    }


    /* =========================
       BOOKED SEATS
    ========================= */
    public function getBookedSeats($date, $time)
    {
        return $this->repo->getBookedSeats($date, $time);
    }

    /* =========================
       DELETE
    ========================= */
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* =========================
       CUSTOMER LIST
    ========================= */
    public function getCustomerReservations($userId)
    {
        return $this->repo->getByUser($userId)->map(function ($r) {

            return [
                'id' => $r->id,
                'guests' => $r->guests,
                'date' => $r->date,
                'time' => $r->time,
                'status' => $r->status,
                'actions' => '<button class="btn btn-danger btn-sm cancelBtn" data-id="'.$r->id.'">Cancel</button>'
            ];
        });
    }
}