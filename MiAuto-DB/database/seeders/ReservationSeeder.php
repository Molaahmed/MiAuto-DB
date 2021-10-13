<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ReservationSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('nl_NL');
        $usersIDs = DB::table('users')->pluck('id');
        $carsIDs = DB::table('cars')->pluck('vin_number');

        for ($i=0; $i < 5; $i++) {
            DB::table('reservations')->insert([
                
                'vin_number'=> $faker->randomElement($carsIDs), 
                'user_id'=>  $faker->randomElement($usersIDs),
                'description' => $faker->text(),
                'date' => $faker->date()
            ]);
        }

    }
}
