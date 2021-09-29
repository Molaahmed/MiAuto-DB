<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    
    function logIn(Request $request){

        $user = User::where('email', $request->email)->first();
        
        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => ['These credentials do not match out records.'] 
            ],404); 
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' =>$token
        ];

        $user->assignRole('garage_manager');
        
        return response($response, 201);
    }

    function singUp(Request $request){

    }
}
