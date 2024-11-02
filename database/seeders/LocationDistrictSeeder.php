<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationDistrictSeeder extends Seeder
{
    public function run(): void
    {
        $cities = include(storage_path('seeders/LocationDistrictSeederData.php'));

        DB::table('location_districts')->insert($cities);
    }
}
