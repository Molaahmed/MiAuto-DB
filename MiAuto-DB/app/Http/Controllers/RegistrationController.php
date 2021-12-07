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
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

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

        //create a Token
        $token = $user->createToken('authToken')->plainTextToken;


        //assign a client role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => 1
        ]);
         

        return response()->json($user, 200);
    }



    public function storeGarage(Request $request)
    {

        // create user as garage owner
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        //create a Token
        $token = $user->createToken('authToken')->plainTextToken;


        $validated = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'required',
            'address'=> 'required|min:3',
            'email' => 'required|email',
            'phone_number'=> 'required|numeric',
            //garage
            'name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);
        
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
         }

        //assign a client role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => 4
        ]);

        //garage 
        $garage = Garage::create([
            'user_id'=> $user->id,
            'name' => $request->name,
            'address' => $request->garage_address,
            'email' => $request->garage_email,
            'phone_number' => $request->garage_phone_number,
        ]);

        Employee::create([
            'user_id' => $user->id,
            'garage_id' => $garage->id
        ]);

        return response()->json($garage, 200);
    }

}
