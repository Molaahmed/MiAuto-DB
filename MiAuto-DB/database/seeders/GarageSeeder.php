<?php

namespace Database\Seeders;

require_once 'vendor/autoload.php';
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Log;

class GarageSeeder extends Seeder
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
            DB::table('garages')->insert([
                'user_id'=> $faker->randomElement($usersIDs),
                'name' => $faker->name(),
                'email' => $faker->email(),
                'address' => $faker->address(), 
                'phone_number'=> $faker->e164PhoneNumber()
            ]);
        }
    }
}
