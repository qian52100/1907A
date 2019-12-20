<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    public function userDo()
    {
       $data=request()->except('_token');
       $user=User::where($data)->find(1);
       if($user){
           session(['user'=>$user]);
           request()->session()->save();
           return redirect('/article');
       }
    }



}
