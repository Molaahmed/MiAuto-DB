<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Garage;
use Illuminate\Support\Facades\DB;

class GarageAdminController extends Controller
{
    public function registerCar(Request $request)
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
            return response()->json(['error: duplicate entry' => 'Car already exists']);
        }
        //
        return Car::create([
            'vin_number' =>  $request->vin_number,
            'client_id' => $request->client_id,
            'garage_id' => $request->garage_id
            ]);
    }

    public function registerClient(Request $request)
    {
        //check if the client exists
        if(User::where('email',$request->email)->first())
        {
            return response()->json(['error: duplicate entry' => 'User already exists']);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ]);
        
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role-id' => 1
        ]);

    }
}
