<?php
namespace Database\Seeders;

require_once 'vendor/autoload.php';
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role;

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
        $role_user = Role::where('name', 'garage_client')->first();
        $role_mechanic = Role::where('name', 'mechanic')->first();
        $role_garage_administration = Role::where('name', 'garage_administration')->first();
        $role_garage_manager = Role::where('name', 'garage_manager')->first();
        $role_admin = Role::where('name', 'admin')->first();


        $faker = Faker::create('nl_NL');
        //
        for ($i=0; $i < 5; $i++) { 
            // DB::table('users')->insert([
            //     'first_name' => $faker->firstName($gender = 'male'|'female'),
            //     'last_name' => $faker->lastName(),
            //     'email' => $faker->email(),
            //     'date_of_birth' => $faker->dateTimeBetween('-1 week', '+1 week'),
            //     'address' => $faker->address(), 
            //     'phone_number'=> $faker->e164PhoneNumber(),
            //     'password' => Hash::make('password'),
            // ]);

            $user = new User();
            $user->first_name = $faker->firstName($gender = 'male'|'female');
            $user->last_name = $faker->lastName();
            $user->email = $faker->email();
            $user->date_of_birth = $faker->dateTimeBetween('-1 week', '+1 week');
            $user->address = $faker->address();
            $user->phone_number = $faker->e164PhoneNumber();
            $user->password = Hash::make('password');
            $user->save();
            $user->roles()->attach($role_user);

        }

        for ($i=0; $i < 5; $i++) { 
    
            $user_mechanic = new User();
            $user_mechanic->first_name = $faker->firstName($gender = 'male'|'female');
            $user_mechanic->last_name = $faker->lastName();
            $user_mechanic->email = $faker->email();
            $user_mechanic->date_of_birth = $faker->dateTimeBetween('-1 week', '+1 week');
            $user_mechanic->address = $faker->address();
            $user_mechanic->phone_number = $faker->e164PhoneNumber();
            $user_mechanic->password = Hash::make('password');
            $user_mechanic->save();
            $user_mechanic->roles()->attach($role_mechanic);
        }

        $user_garage_administration = new User();
        $user_garage_administration->first_name = $faker->firstName($gender = 'male'|'female');
        $user_garage_administration->last_name = $faker->lastName();
        $user_garage_administration->email = $faker->email();
        $user_garage_administration->date_of_birth = $faker->dateTimeBetween('-1 week', '+1 week');
        $user_garage_administration->address = $faker->address();
        $user_garage_administration->phone_number = $faker->e164PhoneNumber();
        $user_garage_administration->password = Hash::make('password');
        $user_garage_administration->save();
        $user_garage_administration->roles()->attach($role_garage_administration);


        $user_garage_manager = new User();
        $user_garage_manager->first_name = $faker->firstName($gender = 'male'|'female');
        $user_garage_manager->last_name = $faker->lastName();
        $user_garage_manager->email = $faker->email();
        $user_garage_manager->date_of_birth = $faker->dateTimeBetween('-1 week', '+1 week');
        $user_garage_manager->address = $faker->address();
        $user_garage_manager->phone_number = $faker->e164PhoneNumber();
        $user_garage_manager->password = Hash::make('password');
        $user_garage_manager->save();
        $user_garage_manager->roles()->attach($role_garage_manager);

        $admin = new User();
        $admin->first_name = $faker->firstName($gender = 'male'|'female');
        $admin->last_name = $faker->lastName();
        $admin->email = $faker->email();
        $admin->date_of_birth = $faker->dateTimeBetween('-1 week', '+1 week');
        $admin->address = $faker->address();
        $admin->phone_number = $faker->e164PhoneNumber();
        $admin->password = Hash::make('password');
        $admin->save();
        $admin->roles()->attach($role_admin);
    }
}
