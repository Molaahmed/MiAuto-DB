<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;

class AdminController extends Controller
{
    public function createCar(Request $request)
    {
        //checks if it's a exisiting user
        if(!User::where('id', $request->client_id)->first())
        {
            return response()->json(['error' => 'Client not found'], 422);
        }
        //checks if the car already exists
        if(Car::where('vin_number',$request->vin_number)->first())
        {
            return response()->json(['duplicate entry' => 'Car already exists']);
        }
        //
        return Car::create([
            'vin_number' =>  $request->vin_number,
            'client_id' => $request->client_id
        ]);
    }
}
