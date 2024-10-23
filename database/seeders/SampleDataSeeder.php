<?php

namespace Database\Seeders;

use App\Constants\Role;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Title;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        /** @var Admin $admin */
        $staff = Admin::create([
            'username' => 'staff',
            'email' => 'staff@test.com',
            'password' => Hash::make('123456')
        ]);
        $staff->assignRole(Role::ROLE_STAFF);

        $division1 = Department::create([
            "name" => "Division 1",
            "code" => "D1",
        ]);
        $division2 = Department::create([
            "name" => "Division 2",
            "code" => "D2",
        ]);

        $managerTitle = Title::create([
            'name' => 'Manager',
            'code' => 'MAN',
        ]);
        $devTitle = Title::create([
            'name' => 'Developer',
            'code' => 'DEV',
        ]);

        Employee::create([
            'emp_no' => 1,
            'first_name' => $faker->firstName(true),
            'last_name' => $faker->firstName(true),
            'gender' => 'M',
            'hire_date' => $faker->date(),
            'birth_date' => $faker->date(),
            'salary' => $faker->randomNumber(8),
            'department_id' => $division1->id,
            'title_id' => $managerTitle->id,
        ]);

        Employee::create([
            'emp_no' => 2,
            'first_name' => $faker->firstName(false),
            'last_name' => $faker->firstName(false),
            'gender' => 'F',
            'hire_date' => $faker->date(),
            'birth_date' => $faker->date(),
            'salary' => $faker->randomNumber(8),
            'department_id' => $division1->id,
            'title_id' => $devTitle->id,
        ]);

        Employee::create([
            'emp_no' => 3,
            'first_name' => $faker->firstName(true),
            'last_name' => $faker->firstName(true),
            'gender' => 'M',
            'hire_date' => $faker->date(),
            'birth_date' => $faker->date(),
            'salary' => $faker->randomNumber(8),
            'department_id' => $division2->id,
            'title_id' => $managerTitle->id,
        ]);

        Employee::create([
            'emp_no' => 4,
            'first_name' => $faker->firstName(false),
            'last_name' => $faker->firstName(false),
            'gender' => 'F',
            'hire_date' => $faker->date(),
            'birth_date' => $faker->date(),
            'salary' => $faker->randomNumber(8),
            'department_id' => $division2->id,
            'title_id' => $devTitle->id,
        ]);
    }
}
