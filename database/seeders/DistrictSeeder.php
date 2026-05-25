<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\State;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        /* ================= LOAD JSON ================= */
        $filePath = database_path('data/districts.json');

        if (!file_exists($filePath)) {
            $this->command->error('❌ districts.json file not found!');
            return;
        }

        $data = json_decode(file_get_contents($filePath), true);

        if (!$data) {
            $this->command->error('❌ Invalid JSON format!');
            return;
        }

        /* ================= OPTIONAL CLEAN ================= */
        // ⚠ Use only in fresh setup (will delete all districts)
        // District::truncate();

        /* ================= INSERT DATA ================= */
        foreach ($data as $item) {

            // ✅ Trim + lowercase for safe matching
            $stateName = trim(strtolower($item['state']));

            $state = State::whereRaw('LOWER(name) = ?', [$stateName])->first();

            if (!$state) {
                $this->command->warn("⚠ State not found: {$item['state']}");
                continue;
            }

            foreach ($item['districts'] as $districtName) {

                $districtName = trim($districtName);

                if (!$districtName) continue; // skip empty values

                District::updateOrCreate(
                    [
                        'name' => $districtName,
                        'state_id' => $state->id
                    ],
                    [
                        'status' => 1
                    ]
                );
            }
        }

        /* ================= SUCCESS MESSAGE ================= */
        $this->command->info('✅ District seeding completed successfully!');
    }
}