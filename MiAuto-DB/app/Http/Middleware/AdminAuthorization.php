<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminAuthorization
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
        $role = DB::table('users')
        ->join('user_role','user_role.user_id','=','users.id')
        ->select('user_role.role_id')->value('role_id');
        
        if($role != 5)
        {
            abort(403, 'Access denied');
        }
        
        return $next($request);
    }
}
