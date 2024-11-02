<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationWardSeeder extends Seeder
{
    public function run(): void
    {
        $cities = include(storage_path('seeders/LocationWardSeederData.php'));

        DB::table('location_wards')->insert($cities);
    }
}
