<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\User;
use App\Models\Garage;
use App\Models\Employee;
use App\Services\PayUService\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\JsonResponse;

class GarageAdminController extends Controller
{

    public function index()
    {
        return Auth::user()->garages;
    }



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
            'email' => 'email|required|unique:users',
            // //employee table
            // 'salary' => 'numeric',
            // //role
             'role' => 'numeric',
        ]);

        if($validated ->fails()){
            return new JsonResponse(['errors'=>$validated->messages()],422);
        }
        
        //check if the manager exists
        if(User::where('email',$request->email)->first())
        {
            return new JsonResponse(['error: duplicate entry' => 'User already exists'],422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'password' => Hash::make("password")
        ]);

        DB::table('employees')->insert([
            'user_id' => $user->id,
            'garage_id' => $request->garage_id,
            'salary' => 0
        ]);
        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
           $user->delete();
           return new JsonResponse ('Error: Not a valid role',403);
        }
        //assign a role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => $request->role
        ]);

        return new JsonResponse("Created successfully", 200);
    }

    public function modifyEmployee(Request $request, $employee_id)
    {
        if(DB::table('employees')
        ->where('user_id', $employee_id)
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
            'email' => 'email|required',
            //role
             'role' => 'numeric',

        ]);

        if ($validated->fails()) {
            return new JsonResponse(['errors'=>$validated->messages()],422);
        }

        User::where('id', $employee_id)
        ->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password)
        ]);


        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
            abort(403, 'error: Not a valid role');
        }

        DB::table('user_role')
        ->where('user_id', $employee_id)
        ->update([
            'role_id' => $request->role
        ]);

        return new JsonResponse("Successfully updated ", 200);
    }

    public function getEmployees(Request $request)
    {
        
        return DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->join('roles','roles.id','=','user_role.role_id')
        ->join('employees','employees.user_id','users.id')
        ->where('employees.garage_id',$request->garage_id)
        ->Where('user_role.role_id', 2)
        ->orWhere('user_role.role_id', 4)
        ->select('users.id','users.first_name','users.last_name','users.email','users.phone_number' ,'users.date_of_birth','users.address','roles.name as role','employees.salary')
        ->get();
    }


    
}
