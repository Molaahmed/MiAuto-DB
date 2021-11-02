<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Garage;

class RegistrationController extends Controller
{
    
    public function storeClient(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address'=> 'required',
            'phone_number'=> 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return abort(406, 'Email exists');
        }

        $user = User::create([
            'first_name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('garage_client');
         

        return response()->json($user, 200);
    }



    public function storeGarage(Request $request)
    {


        $validated = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'address'=> 'required|min:3',
            'email' => 'required|email',
            'phone_number'=> 'required|numeric',
        ]);
        
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
         }

        $garage = Garage::create([
            'user_id'=> Auth::user()->id,
            'name' => $request->name,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json($garage, 200);
    } 

    //Needs to be implemented
    // public function storeMechanic(Request $request)
    // {
    //     $this->validate(request(), [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if ($user) {
    //     return abort(406, 'Email exists');
    //     }

    //     $user = User::create(request(['name', 'email', 'password']));
    //     $user->assignRole('mechanic');

    //     return $user;
    // }
}
