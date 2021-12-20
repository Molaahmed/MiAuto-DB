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
        $validator = Validator::make($request->all(),[
            'user_id' => 'required',
            'vin_number' => 'required|unique:cars',
            'plate' => 'required',
            'type' => 'required',
            'fuel' => 'required',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'gear_box' => 'required',
            'air_conditioner' => 'required',
            'color' => 'required',
        ]);

        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }else{
            $car = Car::create($request->all());
            return new JsonResponse("Successfully created ", 200);
        }
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
            'garage_id'=> 'required',
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
        if($request->role == 1 || $request->role == 5)
        {
           
           return new JsonResponse ('Error: Not a valid role',403);
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
        if(!$user->exists){
            return new JsonResponse ('Employee not saved',403);
        }

        $employee = new Employee;
        $employee->user_id  = $user->id;
        $employee->garage_id = $request->garage_id;
        $employee->salary = 0;
        $saved = $employee->save();
        
        if(!$saved){
            $user->delete();
            return new JsonResponse ('Employee not saved',403);
        } 

        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
            $user->delete();
            $employee->delete();
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
            return new JsonResponse(['error: record not found' => 'Employee not found'],422);
        }

        $validated = Validator::make($request->all(), [
            //user table
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'email|required',
            'date_of_birth' => 'required',
            'address' => 'required|min:2',
            'phone_number' => 'required|min:5',
            //role
             'role' => 'numeric',

        ]);

        if ($validated->fails()) {
            return new JsonResponse(['errors'=>$validated->messages()],422);
        }
        //role reserved for client and admin
        if($request->role == 1 || $request->role == 5)
        {
            return new JsonResponse('error: Not a valid role',403);
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
        ->select('users.id','users.first_name','users.last_name','users.email','users.phone_number' ,'users.date_of_birth','users.address','roles.name as role','employees.salary')
        ->get();
    }


    
}
