<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //type_account
        /*
        1 =>admin
        2 =>pha_che
        3 =>thu_ngan
        */

        if (Auth::check() &&  Auth::user()->type_account==1) {
            return $next($request);
        }
        
        abort(403, 'Bạn không có quyền truy cập');
    }
}
