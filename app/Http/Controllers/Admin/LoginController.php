<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Model\Admin;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.login.index');
    }
    public function loginDo(){
        $data=request()->except('_token');
        $user=Admin::where($data)->find(1);
        if($user){
            session(['user'=>$user]);
            request()->session()->save();
            return redirect('/brand');
        }
    }

    public function dologin(){
        $data=request()->except('_token');
        if (Auth::attempt($data)) {
            // 认证通过...
            return redirect()->intended('/brand');
        }else{
            return redirect('/login')->with('msg','账号或密码错误');
        }

    }


}
