<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GarageAdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [TokenController::class, 'store']);
Route::get('/logout', [TokenController::class, 'destroy']);

Route::post('/client/register', [RegistrationController::class, 'storeClient']);
Route::post('/garage/register', [RegistrationController::class, 'storeGarage']);

//check if the user is authenticated
Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});

// authentication middleware
Route::middleware('auth:sanctum')->group( function(){
    //user
    Route::get('/user' ,[UserController::class, 'User']);
    Route::put('/users/update', [UserController::class, 'updateProfile']);
});



//REMINDER: thess endpoints needs an garage admin authorization
//car
Route::post('/cars/create', [GarageAdminController::class,'registerCar']);

//client
Route::post('/client/create',[GarageAdminController::class,'registerClient'])->middleware('admin');
//emplye
Route::post('/employee/create',[GarageAdminController::class,'registerEmployee']);