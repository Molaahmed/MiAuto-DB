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
use App\Http\Controllers\CarController;


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
    Route::post('/reservation' ,[ReservationController::class, 'store']);
    Route::get('/garages' ,[GarageController::class, 'index']);
    Route::get('/garages/address/{address}' ,[GarageController::class, 'searchByAddress']);
    Route::get('/garages/{id}' ,[GarageController::class, 'show']);
    Route::get('/cars' ,[CarController::class, 'index']);
});


// Authorization : Garage Administrator
Route::middleware(['auth:sanctum','garage.admin'])->group(function() {
    //car
    Route::post('/cars/create', [GarageAdminController::class,'registerCar']);
    Route::get('/garage', [GarageAdminController::class,'index']);
    //client
    Route::post('/client/create',[GarageAdminController::class,'registerClient']);
    //employee
    Route::post('/employee/create',[GarageAdminController::class,'registerEmployee']);
    Route::put('/employee/update/{employee_id}',[GarageAdminController::class,'modifyEmployee']);
    Route::get('/employees/{garage_id}',[GarageAdminController::class,'getEmployees']);
    //reservations
    Route::get('/reservations/{garage_id}' ,[ReservationController::class, 'getByGarageId']);
    Route::post('/reservations/update',[ReservationController::class,'updateReservation']);
    Route::post('/garage/client/register', [RegistrationController::class, 'storeClient']);
    Route::put('/garage/client/update/{client_id}', [UserController::class, 'updateClientProfile']);
    Route::get('/garage/client/cars/{client_id}' ,[ClientCarController::class, 'show']);
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
    Route::put('/client/cars/{car_id}' ,[ClientCarController::class, 'update']);
    Route::get('/client/cars/{client_id}' ,[ClientCarController::class, 'show']);
    Route::delete('/client/cars/{car_id}' ,[ClientCarController::class, 'destroy']);
    Route::get('/reservation' ,[ReservationController::class, 'index']);
});


