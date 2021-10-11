<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TokensController extends Controller
{
    
    public function store(Request $request)
    {
        // $user = User::where('email', $request->email)->first();
        
        // if(!$user || !Hash::check($request->password, $user->password)){
        //     return response([
        //         'message' => ['These credentials do not match out records.'] 
        //     ],404); 
        // }

        // $token = $user->createToken('my-app-token')->plainTextToken;
        // $user->getPermissionsViaRoles();

        // $response = [
        //     'user' => $user,
        //     'token' =>$token, 
        // ];

        
        // return response($response, 201);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Auth::user()->getPermissionsViaRoles();
            
            return Auth::user();
        }

              return response([
                'message' => ['These credentials do not match out records.'] 
            ],404);

    }


    public function destroy(Request $request)
    {
        Auth::logout();
    }

}
