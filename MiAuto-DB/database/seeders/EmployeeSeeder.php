<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class EmployeeSeeder extends Seeder
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
        $gargesIDs = DB::table('garages')->pluck('id');

        for ($i=0; $i < 10; $i++) { 
            DB::table('employees')->insert([
                'user_id'=> $faker->randomElement($usersIDs), 
                'garage_id'=>$faker->randomElement($gargesIDs),
                'salary'=> 200
            ]);

        }
    }
}
