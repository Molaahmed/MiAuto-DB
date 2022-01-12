<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class UserController extends Controller
{



    

    /**
     * GET api/users
     * 
     * Returns all the clients.
     * 
     * @response {"data":[{"id":1,"first_name":"Vaj\u00e8n","last_name":"Joosten","email":"chloe48@hoeks.nl","date_of_birth":"2021-12-25","address":"van Duvenvoirdestraat 16\n1949AN Beek","phone_number":"+316677260032"},{"id":2,"first_name":"Aaron","last_name":"van der Velden","email":"isis.peters@heinrichs.com","date_of_birth":"2021-12-21","address":"Blomring 1-p\n2685VZ Zwijndrecht","phone_number":"+8555950674310"}]}
     *
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */

    public function index()
    {
        $clients = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->where('role_id', '1')
        ->select('*')
        ->get();
        return  UserResource::collection($clients);
        
    }



    /**
     * GET api/user
     * 
     * Returns the data for the user that is authenticated.
     * 
     * @response {"id":11,"first_name":"Dave","last_name":"van der Kaay","email":"amin.tahiri@ismail.nl","date_of_birth":"2021-12-18","address":"Ko\u00e7dreef 5-7\n3551GH Tilburg","phone_number":"+298159732","role":"garage_administration"}
     * @response status=401 { "message": "Unauthenticated." }
     * 
     * @authenticated
     */
    public function User()
    {
        return new JsonResponse( DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->join('roles','roles.id','=','user_role.role_id')
        ->where('users.id',Auth::user()->id)
        ->select('users.id','users.first_name','users.last_name', 'users.email' ,'users.date_of_birth' ,'users.address' ,'users.phone_number','roles.name as role')
        ->first(), 200);
    }



    /**
     * PUT api/user/update
     * 
     * Update the user that is authenticated.
     * 
     * @response scenario=success { "message": "Updated successful" }
     * @response status=422 {"errors":{"first_name":["The first name field is required."],"last_name":["The last name field is required."],"email":["The email field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."]}}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address'=> 'required',
            'phone_number'=> 'required'
        ]);

        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }else{
            $user->update($request->all());

            return new JsonResponse('Updated successful', 200);
        }
    }


    /**
     * PUT api/garage/client/update/{client_id}
     * 
     * Update client of the garage.
     * 
     * 
     * @response scenario=success { "message": "Updated successful" }
     * @response status=404 { "message": "Not Found." }
     * @response status=422 {"errors":{"first_name":["The first name field is required."],"last_name":["The last name field is required."],"email":["The email field is required."],"date_of_birth":["The date of birth field is required."],"address":["The address field is required."],"phone_number":["The phone number field is required."]}}
     * @response status=401 { "message": "Unauthenticated." }
     * @authenticated
     */
    public function updateClientProfile(Request $request, $client_id)
    {

        $user = User::findOrFail($client_id);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|required',
            'date_of_birth' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
        ]);
        if($validator->fails()){
            return new JsonResponse(['errors'=>$validator->messages()],422);
        }
        else{
            $user->update($request->all());
            return new JsonResponse("Successfully updated ", 200);
        }

    }
}
