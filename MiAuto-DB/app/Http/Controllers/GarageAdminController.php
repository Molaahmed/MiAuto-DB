<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\User;
use App\Models\Garage;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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
        //assign a client role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => 1
        ]);

    }

    public function registerEmployee(Request $request)
    {
        $validated = Validator::make($request->all(), [
            //user table
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'required',
            'address' => 'required|min:2',
            'phone_number' => 'required|min:5',
            'password' => 'required|min:8',
            //employee table
            'salary' => 'numeric',
            //role
            'role' => 'numeric',
        ]);
        
        //check if the manager exists
        if(User::where('email',$request->email)->first())
        {
            return response()->json(['error: duplicate entry' => 'User already exists'],422);
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

        DB::table('employees')->insert([
            'user_id' => $user->id,
            'garage_id' => $request->garage_id,
            'salary' => $request->salary
        ]);
        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
            abort(403, 'error: Not a valid role');
        }
        //assign a role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => $request->role
        ]);
    }

    public function modifyEmployee(Request $request)
    {
        if(DB::table('employees')
        ->where('user_id',$request->employee_id)
        ->where('garage_id',$request->garage_id)->count() == 0)
        {
            return response->json(['error: record not found' => 'Employee not found'],422);
        }

        $validated = Validator::make($request->all(), [
            //user table
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'date_of_birth' => 'required',
            'address' => 'required|min:2',
            'phone_number' => 'required|min:5',
            'password' => 'required|min:8',
            //employee table
            'salary' => 'numeric',
            //role
            'role' => 'numeric',

        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }

        User::where('id',$request->employee_id)
        ->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ]);

        Employee::where('user_id',$request->employee_id)
        ->update([
            'salary' => $request->salary
        ]);

        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
            abort(403, 'error: Not a valid role');
        }

        DB::table('user_role')
        ->where('user_id',$request->employee_id)
        ->update([
            'role_id' => $request->role
        ]);
    }

    public function getEmployees(Request $request)
    {
        return DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->join('roles','roles.id','=','user_role.role_id')
        ->join('employees','employees.user_id','users.id')
        ->where('employees.garage_id',$request->garage_id)
        ->select('users.*','roles.name as role','employees.salary')
        ->get();
    }


    
}
