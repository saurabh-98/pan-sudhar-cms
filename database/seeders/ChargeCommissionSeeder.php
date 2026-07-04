<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Charge;
use App\Models\ChargeCommission;
use Illuminate\Support\Facades\DB;

class ChargeCommissionSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        ChargeCommission::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

      
        $commissions = [

            // PAN Services
            'new_pan_apply'              => [127, 3],
            'pan_correction'             => [127, 3],
            'without_document_pan'       => [150, 10],
            'pan_find'                   => [60, 4],

            // ITR Services
            'file_itr'                   => [200, 9.9],

            // Wallet Services
            'wallet_recharge_fee'        => [0, 0],

            // Aadhaar Services
            'mobile_number_update'       => [220, 3],
            'name_correction'            => [1200, 30],
            'dob_correction'             => [1200, 30],
            'address_update'             => [175, 7.5],
            'father_name_update'         => [175, 7.5],
            'husband_name_update'        => [175, 7.5],
            'gender_update'              => [175, 7.5],
            'email_update'               => [175, 7.5],
            'biometric_update'           => [250, 5],
            'child_aadhaar_enrollment'   => [250, 5],
            'new_aadhaar_apply'          => [400, 10],
            'aadhaar_pvc_card'           => [50, 2],
            'aadhaar_download'           => [0, 0],
            'aadhaar_status_check'       => [0, 0],
            'aadhaar_verification'       => [0, 0],

            // CSC Services
            'pm_kisan_registration'      => [70, 3],
            'ayushman_card'              => [70, 3],
            'income_certificate'         => [120, 3],
            'residence_certificate'      => [120, 3],
            'caste_certificate'          => [120, 3],
            'birth_certificate'          => [0, 0],
            'death_certificate'          => [0, 0],
            'labour_card'                => [70, 3],
            'e_shram_card'               => [70, 3],
            'ration_card'                => [1200, 30],

            // Voter ID Services
            'new_voter_id'               => [70, 3],
            'voter_id_correction'        => [70, 3],
            'voter_id_mobile_update'     => [70, 3],
            'voter_id_address_update'    => [70, 3],
            'voter_id_dob_update'        => [70, 3],

            // Bank Account Services
            'airtel-bank-account'        => [200, 10],
            'indian-bank'                => [250, 5],
            'indian-overseas-bank'       => [250, 5],
            'nsdl-payment-bank'          => [200, 5],
            'jio-payment-bank'           => [150, 5],
            'bank-of-baroda'             => [250, 5],
            'kotak-bank-account'         => [100, 10],
            'sbi-pnb-bank-account'       => [250, 5],
            'bank-of-india'              => [250, 5],

            // Other Services
            'raj_patra'                  => [4500, 50],
            'gst_registration_filing'    => [500, 25],
            'food_licence'               => [250, 50],
            'itr_filing_tds_refund'      => [200, 19.9],
            'dsc_digital_signature'      => [1800, 20],
            'msme_registration'          => [60, 4],
            'import_export_certificate'  => [600, 15],
            'rent_agreement'             => [170, 5],
            'police_verification'        => [70, 3],
            'ncpi_aadhaar_seeding'       => [70, 3],
            'driving_licence_ll_test'    => [750, 25],
            'vehicle_challan_payment'    => [0.3, 0.01],
            'rto_services_road_tax'      => [100, 5],
            'passport_services'          => [150, 5],
        ];

        $rows = [];

        foreach ($commissions as $code => [$executive, $distributor]) {

            $charge = Charge::where('code', $code)->first();

            if (! $charge) {
                continue;
            }

            $rows[] = [
                'charge_id'  => $charge->id,
                'role'       => 'Executive',
                'type'       => $charge->type,
                'value'      => $executive,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $rows[] = [
                'charge_id'  => $charge->id,
                'role'       => 'Distributor',
                'type'       => $charge->type,
                'value'      => $distributor,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ChargeCommission::insert($rows);
    }
}