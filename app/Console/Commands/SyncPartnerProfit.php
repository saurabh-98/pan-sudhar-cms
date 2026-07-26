<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

use App\Services\ProfitDistributionService;

use App\Models\Charge;
use App\Models\ProfitPartnerTransaction;

use App\Models\PanApplication;
use App\Models\PanCorrectionApplication;
use App\Models\PanWithoutDocument;
use App\Models\PanFindHistory;

use App\Models\AadhaarService;
use App\Models\CscService;
use App\Models\BankAccountService;
use App\Models\VoterIdService;
use App\Models\ItrFile;
use App\Models\OtherService;

class SyncPartnerProfit extends Command
{
    /**
     * Console Signature
     */
    protected $signature = 'app:sync-partner-profit';

    /**
     * Description
     */
    protected $description = 'Generate partner profit for existing approved services';

    /**
     * Profit Distribution Service
     */
    protected ProfitDistributionService $distribution;

    /**
     * Constructor
     */
    public function __construct(
        ProfitDistributionService $distribution
    ) {
        parent::__construct();

        $this->distribution = $distribution;
    }

    /**
     * Execute Command
     */
    public function handle()
    {
        $this->info('');

        $this->info('========================================');

        $this->info(' Partner Profit Synchronization Started');

        $this->info('========================================');

        $this->syncPan();

        $this->syncPanCorrection();

        $this->syncPanWithoutDocument();

        $this->syncPanFind();

        $this->syncAadhaar();

        $this->syncCsc();

        $this->syncBank();

        $this->syncVoter();

        $this->syncItr();

        $this->syncOther();

        $this->newLine();

        $this->info('========================================');

        $this->info(' Partner Profit Synchronization Finished');

        $this->info('========================================');

        return self::SUCCESS;
    }

    /*
    |--------------------------------------------------------------------------
    | Skip Existing
    |--------------------------------------------------------------------------
    */

    protected function alreadySynced(
        string $service,
        int $serviceId
    ): bool {

        return ProfitPartnerTransaction::where(

            'service_type',
            $service

        )

        ->where(

            'service_id',
            $serviceId

        )

        ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Executive Commission
    |--------------------------------------------------------------------------
    */

    protected function executiveCommission(
        ?Charge $charge
    ): float {

        if (!$charge) {

            return 0;

        }

        return (float)

            $charge->commissions()

                ->where('role', 'Executive')

                ->where('is_active', true)

                ->value('value');

    }

    /*
    |--------------------------------------------------------------------------
    | Distributor Commission
    |--------------------------------------------------------------------------
    */

    protected function distributorCommission(
        ?Charge $charge
    ): float {

        if (!$charge) {

            return 0;

        }

        return (float)

            $charge->commissions()

                ->where('role', 'Distributor')

                ->where('is_active', true)

                ->value('value');

    }

    /*
    |--------------------------------------------------------------------------
    | Charge Resolver
    |--------------------------------------------------------------------------
    */

    protected function charge(
        string $code
    ): ?Charge {

        return Charge::with('commissions')

            ->active()

            ->where('code', $code)

            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Common Distributor
    |--------------------------------------------------------------------------
    */

    protected function distributor($application)
    {
        if (!$application->user) {
            return null;
        }

        if (!$application->user->created_by) {
            return null;
        }

        return \App\Models\User::find(

            $application->user->created_by

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Profit Distribution Helper
    |--------------------------------------------------------------------------
    */

    protected function distribute(

        string $service,

        $application,

        float $executive,

        float $distributor

    ): void {

        $this->distribution->distribute(

            serviceType: $service,

            serviceId: $application->id,

            referenceNo: $application->application_no
                ?? ('#'.$application->id),

            serviceAmount: (float)

                ($application->amount
                ?? $application->charge
                ?? 0),

            executiveCommission: $executive,

            distributorCommission: $distributor,

            remarks: 'Historical Profit Synchronization'

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Generic Sync Runner
    |--------------------------------------------------------------------------
    |
    | Shared workhorse used by every syncXxx() method below. It walks an
    | approved-service query in chunks, skips rows already synced, resolves
    | the applicable Charge + commissions, and records the profit split.
    |
    | NOTE: This assumes each model exposes a `status` column with an
    | "Approved" value and a `user()` relation used to resolve the
    | distributor. Adjust the $query callback per model if a given service
    | uses a different status column/value or approval flag.
    |
    */

    protected function syncGeneric(

        string $serviceType,

        string $modelClass,

        string $chargeCode,

        ?\Closure $query = null

    ): void {

        $this->line("Syncing: {$serviceType}");

        $synced = 0;

        $skipped = 0;

        $base = $modelClass::query();

        if ($query) {

            $query($base);

        } else {

            $base->where('status', 'Approved');

        }

        $base->orderBy('id')->chunkById(200, function ($applications) use (

            $serviceType,

            $chargeCode,

            &$synced,

            &$skipped

        ) {

            foreach ($applications as $application) {

                if ($this->alreadySynced($serviceType, $application->id)) {

                    $skipped++;

                    continue;

                }

                $charge = $this->charge($chargeCode);

                $executive = $this->executiveCommission($charge);

                $distributor = $this->distributorCommission($charge);

                $this->distribute(

                    $serviceType,

                    $application,

                    $executive,

                    $distributor

                );

                $synced++;

            }

        });

        $this->info("  -> Synced: {$synced}, Skipped (already synced): {$skipped}");

    }

    /*
    |--------------------------------------------------------------------------
    | Service Methods
    |--------------------------------------------------------------------------
    |
    | Each method below wires up a single service model to the generic
    | runner. The service_type strings and charge codes are assumed values —
    | update them to match what's actually stored in ProfitPartnerTransaction
    | and the Charge table if they differ.
    |
    */

    protected function syncPan()
    {
        $this->syncGeneric(

            serviceType: 'pan_application',

            modelClass: PanApplication::class,

            chargeCode: 'PAN'

        );
    }

    protected function syncPanCorrection()
    {
        $this->syncGeneric(

            serviceType: 'pan_correction_application',

            modelClass: PanCorrectionApplication::class,

            chargeCode: 'PAN_CORRECTION'

        );
    }

    protected function syncPanWithoutDocument()
    {
        $this->syncGeneric(

            serviceType: 'pan_without_document',

            modelClass: PanWithoutDocument::class,

            chargeCode: 'PAN_WITHOUT_DOCUMENT'

        );
    }

    protected function syncPanFind()
    {
        $this->syncGeneric(

            serviceType: 'pan_find_history',

            modelClass: PanFindHistory::class,

            chargeCode: 'PAN_FIND'

        );
    }

    protected function syncAadhaar()
    {
        $this->syncGeneric(

            serviceType: 'aadhaar_service',

            modelClass: AadhaarService::class,

            chargeCode: 'AADHAAR'

        );
    }

    protected function syncCsc()
    {
        $this->syncGeneric(

            serviceType: 'csc_service',

            modelClass: CscService::class,

            chargeCode: 'CSC'

        );
    }

    protected function syncBank()
    {
        $this->syncGeneric(

            serviceType: 'bank_account_service',

            modelClass: BankAccountService::class,

            chargeCode: 'BANK_ACCOUNT'

        );
    }

    protected function syncVoter()
    {
        $this->syncGeneric(

            serviceType: 'voter_id_service',

            modelClass: VoterIdService::class,

            chargeCode: 'VOTER_ID'

        );
    }

    protected function syncItr()
    {
        $this->syncGeneric(

            serviceType: 'itr_file',

            modelClass: ItrFile::class,

            chargeCode: 'ITR'

        );
    }

    protected function syncOther()
    {
        $this->syncGeneric(

            serviceType: 'other_service',

            modelClass: OtherService::class,

            chargeCode: 'OTHER'

        );
    }

}