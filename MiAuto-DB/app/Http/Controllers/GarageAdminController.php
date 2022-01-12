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


    /**
     * GET api/garage
     * 
     * Returns the garages of the user that is authenticated.
     * 
     * @response [{"id":1,"user_id":12,"name":"Sten Haselaar","address":"van Dokkumhof 3\n9801TA Westerhaar-Vriezenveensewijk","email":"lveenstra@gmail.com","phone_number":"+41682531929"},{"id":6,"user_id":12,"name":"ut","address":"placeat","email":"hic@gmail.com","phone_number":"+3125544785"}]
     * 
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function index()
    {
        return Auth::user()->garages;
    }


    /**
     * POST api/cars/create
     * 
     * Adding new car as Garage Administrator
     * 
     * @response status=200  {"message": "Successfully created"}
     * @response status=422  {"errors":{"user_id":["The user id field is required."],"vin_number":["The vin number field is required."],"plate":["The plate field is required."],"type":["The type field is required."],"fuel":["The fuel field is required."],"make":["The make field is required."],"model":["The model field is required."],"engine":["The engine field is required."],"gear_box":["The gear box field is required."],"air_conditioner":["The air conditioner field is required."],"color":["The color field is required."]}}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function registerCar(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'vin_number' => 'required|unique:cars',
            'plate' => 'required',
            'type' => 'required',
            'fuel' => 'required',
            'make' => 'required',
            'model' => 'required',
            'engine' => 'required',
            'gear_box' => 'required',
            'air_conditioner' => 'required|boolean',
            'color' => 'required',
        ]);

        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }else{
            $car = Car::create($request->all());
            return new JsonResponse("Successfully created ", 200);
        }
    }

    /**
     * POST api/client/create
     * 
     * Create new client as Garage Administrator 
     * 
     * @bodyParam first_name string required Example: Luuk
     * @bodyParam last_name string required Example: van der Steen
     * @bodyParam date_of_birth string required Example:2001-02-06
     * @bodyParam address string required Example: Juan Leon Mera, 19, Av. Patria
     * @bodyParam phone_number string required Example: +5514123456
     * @bodyParam password string required Example: password
     * 
     * @response status=200
     * @response status=422  {"first_name":["The first name field is required."],"last_name":["The last name field is required."],"email":["The email field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."],"password":["The password field is required."]}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function registerClient(Request $request)
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

        //assign a client role
        DB::table('user_role')->insert([
            'user_id' => $user->id,
            'role_id' => 1
        ]);

        return response()->json(200);
    }



    /**
     * POST api/employee/create
     * 
     * Create new employee.
     * Add that employee to the garage
     * 
     * 
     * @response status=200  {"message": "Successfully created"}
     * @response status=422 {"errors":{"first_name":["The first name field is required."],"last_name":["The last name field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."],"email":["The email field is required."],"garage_id":["The garage id field is required."]}}
     * @response status=403 {"Error": "Not a valid role"}
     * @response status=403 {"Error": "Employee not saved"}
     * @response status=403 {"Error": "Employee not saved"}
     * @response status=401 { "message": "Unauthenticated." } 
     * @authenticated
     */
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



    /**
     * PUT api/employee/update/{employee_id}
     * 
     * Edit employee employee working in the garage.
     * 
     * @response status=200  {"message": "Successfully updated"}
     * @response status=422 {"errors":{"first_name":["The first name field is required."],"last_name":["The last name field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."],"email":["The email field is required."],"garage_id":["The garage id field is required."]}}
     * @response status=403 {"Error": "Not a valid role"}
     * @response status=403 {"Error": "Employee not saved"}
     * @response status=422 {"error: record not found":"Employee not found"}
     * @response status=401 { "message": "Unauthenticated." } 
     * @authenticated 
     */
    public function modifyEmployee(Request $request, $employee_id)
    {


        $validated = Validator::make($request->all(), [
            //user table
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'email' => 'email|required',
            'date_of_birth' => 'required',
            'address' => 'required|min:2',
            'phone_number' => 'required|min:5',
            'garage_id' => 'required|integer',
            //role
             'role' => 'numeric',

        ]);

        if ($validated->fails()) {
            return new JsonResponse(['errors'=>$validated->messages()],422);
        }

        if(DB::table('employees')
        ->where('user_id', $employee_id)
        ->where('garage_id',$request->garage_id)->count() == 0)
        {
            return new JsonResponse(['error: record not found' => 'Employee not found'],422);
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



    /**
     * GET api/employees/{garage_id}
     * 
     * Returning all the employees in the garage.
     * 
     * @response [{"id":6,"first_name":"Chris","last_name":"Blom","email":"jdachgeldt@vanderberg.com","phone_number":"+23671905652","date_of_birth":"2021-12-27","address":"Kriensstraat 87-u\n4401CG Kommerzijl","role":"mechanic","salary":500},{"id":7,"first_name":"Lola","last_name":"Dirksen","email":"mvanhetheerenveen@winnrich.nl","phone_number":"+600830017408","date_of_birth":"2021-12-14","address":"Moetdreef 6\n9269SZ Wekerom","role":"mechanic","salary":200},{"id":12,"first_name":"Benjamin","last_name":"Zu\u00e9rius Boxhorn van Miggrode","email":"pham.maud@vanembden.org","phone_number":"+6802172188","date_of_birth":"2021-12-24","address":"van de Walweg 94-p\n7606XL Poortvliet","role":"garage_administration","salary":200}]
     * 
     * @response status=401 { "message": "Unauthenticated." } 
     * @authenticated 
     */
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
