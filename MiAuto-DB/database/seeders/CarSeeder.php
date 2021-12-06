<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = (new \Faker\Factory())::create();
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));
        $usersIDs = DB::table('users')->pluck('id');
    
        for ($i=0; $i < 5; $i++) { 
    
            DB::table('cars')->insert([
                'vin_number'=> $faker->vin,
                'user_id'=> $faker->randomElement($usersIDs),
                'plate' => $faker->vehicleRegistration,
                'type' => $faker->vehicleType,
                'fuel' => $faker->vehicleFuelType,
                'make' => $faker->vehicleBrand,
                'model' =>  $faker->vehicleModel,
                'engine' => $faker->vehicleModel,
                'gear_box' => $faker->vehicleGearBoxType,
                'air_conditioner'=> true,
                'color' => 'red',
            ]);
        }
    }
}
