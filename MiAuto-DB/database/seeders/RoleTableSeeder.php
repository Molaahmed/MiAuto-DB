<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->id = 1;
        $role_user->name = "garage_client";
        $role_user->description = "Normal user";
        $role_user->save();

        $role_mechanic = new Role();
        $role_mechanic->id = 2;
        $role_mechanic->name = "mechanic";
        $role_mechanic->description = "Mechanic working in the garage";
        $role_mechanic->save();

        $role_garage_administration = new Role();
        $role_garage_administration->id = 3;
        $role_garage_administration->name = "garage_administration";
        $role_garage_administration->description = "Admin of the garage";
        $role_garage_administration->save();
        
        $role_garage_manager = new Role();
        $role_garage_manager->id = 4;
        $role_garage_manager->name = "garage_manager";
        $role_garage_manager->description = "Owner of the garage";
        $role_garage_manager->save();

        $role_admin = new Role();
        $role_admin->id = 5;
        $role_admin->name = "admin";
        $role_admin->description = "admin";
        $role_admin->save();
    }
}
