<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
   

    //Creating all roles with guard API
    // $role = Role::create(['guard_name' => 'api', 'name' => 'client']);
    // $role = Role::create(['guard_name' => 'api','name' => 'mechanic']);
    // $role = Role::create(['guard_name' => 'api','name' => 'garage_administration']);
    // $role = Role::create(['guard_name' => 'api','name' => 'garage_manager']);

});
