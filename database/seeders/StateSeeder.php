<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use Illuminate\Support\Str;

class StateSeeder extends Seeder
{
    public function run()
    {
        // State::truncate(); // optional

        $states = [
            'Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh',
            'Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand',
            'Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur',
            'Meghalaya','Mizoram','Nagaland','Odisha','Punjab',
            'Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura',
            'Uttar Pradesh','Uttarakhand','West Bengal',

            // UT
            'Andaman and Nicobar Islands','Chandigarh',
            'Dadra and Nagar Haveli and Daman and Diu',
            'Delhi','Jammu and Kashmir','Ladakh',
            'Lakshadweep','Puducherry'
        ];

        foreach ($states as $state) {

            $state = trim($state);

            State::firstOrCreate(
                ['name' => $state],
                [
                    'status' => 1,
                    'slug'   => Str::slug($state)
                ]
            );
        }

        $this->command->info('✅ States seeded successfully!');
    }
}