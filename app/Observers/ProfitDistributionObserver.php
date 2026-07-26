<?php

namespace App\Observers;

use App\Services\ProfitDistributionService;

class ProfitDistributionObserver
{
    /**
     * Handle the updated model event.
     */
    public function updated($model): void
    {
        // Only when status changes to Approved
        if (
            $model->wasChanged('status') &&
            strtolower($model->status) === 'Approved'
        ) {

            app(ProfitDistributionService::class)
                ->handle($model);

        }
    }
}