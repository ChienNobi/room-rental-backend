<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var Admin $admin */
        $admin = Admin::create([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456')
        ]);
    }
}
