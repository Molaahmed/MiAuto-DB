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
        $faker = Faker::create('nl_NL');
        $usersIDs = DB::table('users')->pluck('id');
    
        for ($i=0; $i < 5; $i++) { 
            DB::table('cars')->insert([
                'vin_number'=> $faker->regexify('[A-Za-z0-9]{17}'),
                'client_id'=> $faker->randomElement($usersIDs),
                'garage_id'=> $faker->randomElement($garageIDs)
            ]);
        }

    }
}
