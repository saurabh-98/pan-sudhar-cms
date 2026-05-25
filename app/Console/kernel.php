<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected function schedule(
        Schedule $schedule
    ): void {

        /*
        |--------------------------------------------------------------------------
        | FEE REMINDER SYSTEM
        |--------------------------------------------------------------------------
        */

        $schedule->command(

            'fees:reminders'

        )

        /*
        |--------------------------------------------------------------------------
        | DAILY MORNING
        |--------------------------------------------------------------------------
        */

        ->dailyAt('09:00')

        /*
        |--------------------------------------------------------------------------
        | PREVENT OVERLAP
        |--------------------------------------------------------------------------
        */

        ->withoutOverlapping()

        /*
        |--------------------------------------------------------------------------
        | LOG OUTPUT
        |--------------------------------------------------------------------------
        */

        ->appendOutputTo(

            storage_path(
                'logs/fee-reminders.log'
            )

        );
    }

    /**
     * Register commands.
     */

    protected function commands(): void
    {
        $this->load(

            __DIR__.'/Commands'

        );

        require base_path(
            'routes/console.php'
        );
    }
}