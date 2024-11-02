<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationCitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = include(storage_path('seeders/LocationCitySeederData.php'));

        DB::table('location_cities')->upsert($cities, 'slug');
    }
}
