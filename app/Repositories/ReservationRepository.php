<?php
namespace App\Repositories;

use App\Models\Reservation;
use App\Models\Table;

class ReservationRepository
{
    public function getAll()
    {
        return Reservation::latest()->get();
    }

    public function create($data)
    {
        return Reservation::create($data);
    }

    public function find($id)
    {
        return Reservation::findOrFail($id);
    }

    public function updateStatus($id, $status)
    {
        return Reservation::where('id',$id)->update(['status'=>$status]);
    }

    public function getBookedSeats($date, $time)
    {
        return Reservation::where('date', $date)
            ->where('time', $time)
            ->where('status', '!=', 'cancelled')
            ->sum('guests');
    }

    public function checkDuplicateBooking($email, $date, $time)
    {
        return Reservation::where('email', $email)
            ->where('date', $date)
            ->where('time', $time)
            ->exists();
    }

    public function getByUser($userId)
    {
        return Reservation::with('table')
            ->where('user_id', $userId)
            ->get();
    }

    public function getAvailableTables($date, $time, $guests)
    {
        return Table::where('is_active', 1)
            ->where('capacity', '>=', $guests)
            ->whereDoesntHave('reservations', function ($q) use ($date, $time) {
                $q->where('date', $date)
                ->where('time', $time)
                ->where('status', '!=', 'cancelled');
            })
            ->orderBy('capacity') 
            ->get();
    }

    public function delete($id)
    {
        return Reservation::destroy($id);
    }
}