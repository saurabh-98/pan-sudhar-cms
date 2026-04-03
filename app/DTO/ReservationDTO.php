<?php
namespace App\DTO;

class ReservationDTO
{
    public $user_id;
    public $name;
    public $email;
    public $phone;
    public $date;
    public $time;
    public $guests;
    public $notes;
    public $status;

    public function __construct(
        $user_id,
        $name,
        $email,
        $phone,
        $date,
        $time,
        $guests,
        $notes = null,
        $status = 'pending'
    ) {
        $this->user_id = $user_id;
        $this->name    = $name;
        $this->email   = $email;
        $this->phone   = $phone;
        $this->date    = $date;
        $this->time    = $time;
        $this->guests  = $guests;
        $this->notes   = $notes;
        $this->status  = $status; 
    }

    /*  FACTORY METHOD */
    public static function fromRequest($request)
    {
        return new self(
            auth()->id(),
            $request->name,
            $request->email,
            $request->phone,
            $request->date,
            $request->time,
            $request->guests,
            $request->notes ?? null,
            'pending' // force safe default
        );
    }

    /*  CONVERT TO ARRAY */
    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id,
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'date'    => $this->date,
            'time'    => $this->time,
            'guests'  => $this->guests,
            'notes'   => $this->notes,
            'status'  => $this->status,
        ];
    }
}