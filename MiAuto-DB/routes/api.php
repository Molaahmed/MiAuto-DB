<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GarageAdminController;
use App\Http\Controllers\ClientCarController;
use App\Http\Controllers\GarageController;
use App\Http\Controllers\ReservationController;


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
Route::post('/logout', [TokenController::class, 'destroy']);

Route::post('/client/register', [RegistrationController::class, 'storeClient']);
Route::post('/garage/register', [RegistrationController::class, 'storeGarage']);


// Check if authenticated
Route::middleware('auth:sanctum')->get('/authenticated', function () {
    return true;
});


// Authorization : Authentication
Route::middleware('auth:sanctum')->group( function(){
    //user
    Route::get('/user' ,[UserController::class, 'User']);
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/user/update', [UserController::class, 'updateProfile']);
});


// Authorization : Garage Administrator
Route::middleware(['auth:sanctum','garage.admin'])->group(function() {
    //car
    Route::post('/cars/create', [GarageAdminController::class,'registerCar']);
    //client
    Route::post('/client/create',[GarageAdminController::class,'registerClient']);
    //employee
    Route::post('/employee/create',[GarageAdminController::class,'registerEmployee']);
    Route::post('/employee/update',[GarageAdminController::class,'modifyEmployee']);
    Route::get('/employees/{garage_id}',[GarageAdminController::class,'getEmployees']);
    Route::put('/reservation' ,[ReservationController::class, 'store']);
    Route::get('/reservations/{garage_id}' ,[ReservationController::class, 'getByGarageId']);

});

 // Authorization : Garage Employee
 Route::middleware(['auth:sanctum','garage.employee'])->group(function() {
    //Garage Id
    Route::get('/garage/id', [GarageController::class,'getGarageId']);
});

// Authorization : Garage Client
Route::middleware(['auth:sanctum','garage.client'])->group(function() {
    Route::get('/client/cars' ,[ClientCarController::class, 'index']);
    Route::post('/client/cars' ,[ClientCarController::class, 'store']);
    Route::put('/client/cars/{id}' ,[ClientCarController::class, 'update']);
    Route::get('/client/cars/{id}' ,[ClientCarController::class, 'show']);
    Route::delete('/client/cars/{id}' ,[ClientCarController::class, 'destroy']);
    Route::get('/reservation' ,[ReservationController::class, 'index']);
    Route::put('/reservation' ,[ReservationController::class, 'store']);
});


Route::get('/garages' ,[GarageController::class, 'index']);
Route::get('/garages/address/{address}' ,[GarageController::class, 'searchByAddress']);
Route::get('/garages/{id}' ,[GarageController::class, 'show']);



