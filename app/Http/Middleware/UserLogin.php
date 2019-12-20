<?php

namespace App\Http\Middleware;

use Closure;

class UserLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $info=request()->session()->get('userInfo');
        if(!$info){
           echo "<script>alert('请先登陆');location.href='/login';</script>";
        }
        return $next($request);
    }
}
