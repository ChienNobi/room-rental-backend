<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(LocationCitySeeder::class);
        $this->call(LocationDistrictSeeder::class);
        $this->call(LocationWardSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
