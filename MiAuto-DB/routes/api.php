<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokensController;
use App\Http\Controllers\RegistrationController;

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
//Route::post('/registerMechanic', [RegistrationController::class, 'storeMechanic']);

Route::post('/login', [TokensController::class, 'store']);
Route::get('/logout', [TokensController::class, 'destroy']);
