<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function index()
    {
        $clients = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->where('role_id', '1')
        ->select('*')
        ->get();
        return  UserResource::collection($clients);
        
    }

    public function User()
    {
         $role_id = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->select('user_role.role_id')->value('role_id');

        return new JsonResponse( DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->join('roles','roles.id','=','user_role.role_id')
        ->where('users.id',Auth::user()->id)
        ->select('users.id','users.first_name','users.last_name', 'users.email' ,'users.date_of_birth' ,'users.address' ,'users.phone_number','roles.name as role')
        ->first(), 200);

        // return Auth::user();
        //if its a garage owner or employee we return the garage he's/she's working at
        // if($role_id == 2 || $role_id == 3 || $role_id == 4)
        // {
        //     return DB::table('users')
        // ->join('user_role','user_role.user_id','=','users.id')
        // ->join('roles','roles.id','=','user_role.role_id')
        // ->join('employees','employees.user_id','users.id')
        // ->join('garages','garages.user_id','=','users.id')
        // ->where('users.id',Auth::user()->id)
        // ->select('users.*','garages.name as Garage','garages.address as GarageAddress','garages.email as GarageEmail','garages.phone_number as GaragePhoneNumber','roles.name as role','employees.salary')
        // ->first();
        // }

        // else{
        //     return DB::table('users')
        //     ->join('user_role','user_role.user_id','=','users.id')
        //     ->join('roles','roles.id','=','user_role.role_id')
        //     ->where('users.id',Auth::user()->id)
        //     ->select('users.*','roles.name as role')
        //     ->first();
        // // }
        
    }

    public function updateProfile(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        if(!$user)
        {
            return response([
                'message' => ['User not found'] 
            ],404);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'date_of_birth' => 'required',
            'address'=> 'required',
            'phone_number'=> 'required'
        ]);
        
        $user->update($request->all());

        return new JsonResponse('Updated successful', 200);
    }
}
