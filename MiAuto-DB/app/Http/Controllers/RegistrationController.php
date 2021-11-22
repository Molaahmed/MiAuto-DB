<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Models\User;
use App\Models\Garage;

class RegistrationController extends Controller
{
    
    public function storeClient(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);
         

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

}
