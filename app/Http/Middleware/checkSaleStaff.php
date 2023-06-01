<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkSaleStaff
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
        //check user login
        if(Auth::check()){
            if (Auth::user()->type_account == 1 ||  Auth::user()->type_account == 2) {
                return $next($request);
            }
            abort(403, 'Bạn không có quyền truy cập');
        }

        return redirect('admin/login')->with("error_login","vui lòng đăng nhập");
    
    }
}
