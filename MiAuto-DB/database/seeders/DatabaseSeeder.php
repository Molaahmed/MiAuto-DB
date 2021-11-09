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
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call(RoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(GarageSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(CarSeeder::class);
        $this->call(ReservationSeeder::class);
        $this->call(OperationSeeder::class);
    }
}
