<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokensController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});



Route::post('/registerClient', [RegistrationController::class, 'storeClient']);
Route::post('/registerGarage', [RegistrationController::class, 'storeGarage']);

Route::post('/login', [TokenController::class, 'store']);
Route::get('/logout', [TokenController::class, 'destroy']);

Route::put('/users/update', [UserController::class, 'updateProfile']);


//REMINDER: this endpoint needs to have an admin authorization
Route::post('/cars/create', [AdminController::class,'createCar']);