<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GarageAdminAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        //check if he's working at that garage
        $check = DB::table('employees')
        ->where('user_id',Auth::user()->id)
        ->where('garage_id',$request->garage_id)
        ->count();
        
        if($check == 0)
        {
            abort(403, 'Access denied');
        }
    

        //check if the employee is Authorized
        $role = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->select('user_role.role_id')->value('role_id');
    
          if($role != 3)
          {
             abort(403, 'Access denied');
          }

          return $next($request);
    }
}
