<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TokenController extends Controller
{
    

    /**
     * POST api/login
     * 
     * This endpoint is used by all types of users to be authenticated. 
     * In the response, there will be a token that you need to use in every request that you make to the server.
     *
     * <aside class="notice">Use the token in every request that you make in order to be authorized to access resources.</aside>
     * 
     * @bodyParam email string required The email of the user.  Example: example@gmail.com
     * @bodyParam password string required Password of the user. Example: password
     * 
     * @response scenario=success {
     *  "4|va23TB3m66Pr1W7ozSfDuRWMsHnf6fhwexyTY1Wg"
     * }
     * 
     * @response status=422 scenario="user not found" {"message": "These credentials do not match out records."}
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        

        if(Auth::attempt($request->only('email', 'password'))){
            $user = User::where('email',$request->email)->first();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json($token, 200);
        }
        return response([
            'message' => ['These credentials do not match out records.'] 
        ],422);
            

    }

    /**
     * POST api/logout
     * 
     * This endpoint will log out the user and destroy the token that is used.
     */
    public function destroy(Request $request)
    {
        Auth::logout();
    }

}
