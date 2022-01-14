<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Models\Garage;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * POST api/client/register
     * 
     * This endpoint is used to create client. The endpoint is used for development pupposes.
     * 
     * @bodyParam first_name string required Example: Luuk
     * @bodyParam last_name string required Example: van der Steen
     * @bodyParam date_of_birth string required Example:2001-02-06
     * @bodyParam address string required Example: Juan Leon Mera, 19, Av. Patria
     * @bodyParam phone_number string required Example: +5514123456
     * 
     * @group Garage Administrator
     * @response status=200  {"first_name":"Luuk","last_name":"van der Steen","email":"luckeeylssuddukss.633@gmail.com","date_of_birth":"2001-02-06","address":"Juan Leon Mera, 19, Av. Patria","phone_number":"4 123 4567","id":14}
     * @response status=422  {"first_name":["The first name field is required."],"last_name":["The last name field is required."],"email":["The email field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."],"password":["The password field is required."]}
     * 
     */
    public function storeClient(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required|unique:users',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
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


    /**
     * POST api/garage/register
     * 
     * This endpoint will first create a user and then assign the garage to that user.
     * You need to pass the client credentials and the garage in the same request.
     * 
     * @bodyParam first_name string required Example: Luuk
     * @bodyParam last_name string required Example: van der Steen
     * @bodyParam date_of_birth string required Example:2001-02-06
     * @bodyParam address string required Example: Juan Leon Mera, 19, Av. Patria
     * @bodyParam phone_number string required Example: +5514123456
     * 
     * @bodyParam name string required  Name of the garage.
     * @bodyParam garage_address string required Address of the garage.
     * @bodyParam garage_email string required  Email of the garage.
     * @bodyParam garage_phone_number string required  Phone number of the garage.
     * 
     * @response status=200  {"user_id":15,"name":"ut","address":"placeat","email":"hic@gmail.com","phone_number":"+3125544785","id":6}
     * @response status=422  {"first_name":["The first name field is required."],"last_name":["The last name field is required."],"email":["The email field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."],"garage_email":["The garage_email of garage field is required."],"garage_address":["The garage_address of garage field is required."],"name":["The name of garage field is required."],"garage_phone_number":["The garage_phone_number of garage field is required."]}
     */
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
