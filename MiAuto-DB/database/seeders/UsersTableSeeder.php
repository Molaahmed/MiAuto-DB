<?php
namespace Database\Seeders;

require_once 'vendor/autoload.php';
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('nl_NL');
        //
        for ($i=0; $i < 10; $i++) { 
            DB::table('users')->insert([
                'first_name' => $faker->firstName($gender = 'male'|'female'),
                'last_name' => $faker->lastName(),
                'email' => $faker->email(),
                'date_of_birth' => $faker->dateTimeBetween('-1 week', '+1 week'),
                'address' => $faker->address(), 
                'phone_number'=> $faker->e164PhoneNumber(),
                'password' => Hash::make('password'),
            ]);
        }
    }
}
