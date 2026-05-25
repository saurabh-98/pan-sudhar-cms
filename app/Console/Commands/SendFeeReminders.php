<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Admission;

use Carbon\Carbon;

class SendFeeReminders extends Command
{
    protected $signature =
        'fees:reminders';

    protected $description =
        'Send fee reminders';

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | PARTIAL / DUE STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = Admission::where(

                'due_amount',

                '>', 0

            )
            ->whereNotNull(
                'fee_due_date'
            )
            ->get();

        foreach($students as $student){

            $dueDate =
                Carbon::parse(
                    $student->fee_due_date
                );

            /*
            |--------------------------------------------------------------------------
            | BEFORE DUE DATE
            |--------------------------------------------------------------------------
            */

            if(

                now()->isSameDay(

                    $dueDate
                        ->copy()
                        ->subDay()

                )

            ){

                $this->sendReminder(

                    $student,

                    'friendly'

                );
            }

            /*
            |--------------------------------------------------------------------------
            | FIRST REMINDER
            |--------------------------------------------------------------------------
            */

            if(

                now()->gt(

                    $dueDate
                        ->copy()
                        ->addDays(3)

                )

                &&

                !$student
                    ->first_reminder_sent

            ){

                $this->sendReminder(

                    $student,

                    'first'

                );

                $student->update([

                    'first_reminder_sent' => true,

                    'first_reminder_sent_at' => now()

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | FINAL REMINDER
            |--------------------------------------------------------------------------
            */

            if(

                now()->gt(

                    $dueDate
                        ->copy()
                        ->addDays(7)

                )

                &&

                !$student
                    ->final_reminder_sent

            ){

                $this->sendReminder(

                    $student,

                    'final'

                );

                $student->update([

                    'final_reminder_sent' => true,

                    'final_reminder_sent_at' => now()

                ]);
            }
        }

        $this->info(
            'Fee reminders processed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEND REMINDER
    |--------------------------------------------------------------------------
    */

    protected function sendReminder(
        $student,
        $type
    ) {

        if($type === 'friendly'){

            $message =
                'Friendly Reminder: Fee due tomorrow.';
        }

        elseif($type === 'first'){

            $message =
                'First Reminder: Fee payment overdue.';
        }

        else{

            $message =
                'FINAL Reminder: Immediate payment required.';
        }

        /*
        |--------------------------------------------------------------------------
        | SEND SMS / EMAIL / WHATSAPP
        |--------------------------------------------------------------------------
        */

        \Log::info([

            'student' =>
                $student->name,

            'message' =>
                $message
        ]);
    }
}