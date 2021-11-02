<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('nl_NL');
        $garagesIDs = DB::table('garages')->pluck('id');
        $carsIDs = DB::table('cars')->pluck('vin_number');
        $employeesIDs = DB::table('employees')->pluck('id');

        for ($i=0; $i < 5; $i++) {
            DB::table('operations')->insert([
    
                'vin_number'=> $faker->randomElement($carsIDs), 
                'garage_id'=>  $faker->randomElement($garagesIDs),
                'employee_id' => $faker->randomElement($employeesIDs),
                'status' => 'done',
                'date_entered' => $faker->date(),
                'date_exited' => $faker->date(),
                'cost' => 200
            ]);
        }

    }
}
