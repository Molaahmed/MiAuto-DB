<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Garage;
use Illuminate\Support\Facades\DB;

class GarageAdminController extends Controller
{
    public function RegisterCar(Request $request)
    {
	//check if its a valid garage
	    if(!Garage::where('id',$request->garage_id)->first())
	    {
		return response()->json(['error' => 'Garage not found'],422);
	    }
	//checks if the user exists
        if(!User::where('id', $request->client_id)->first())
        {
            return response()->json(['error' => 'Client not found'], 422);
        }
    //checks if the car already exists in the garage
        if(DB::table('cars')->where('vin_number',$request->vin_number)
        ->where('garage_id',$request->garage_id)->count() != 0)
        {
            return response()->json(['duplicate entry' => 'Car already exists']);
        }
        //
        return Car::create([
            'vin_number' =>  $request->vin_number,
            'client_id' => $request->client_id,
            'garage_id' => $request->garage_id
            ]);
    }
}
