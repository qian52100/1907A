<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\Goods;
class IndexController extends Controller
{
    public function index(){

        $cate_id=request()->cate_id;
        $navInfo=Category::where('cate_nav_show','=',1)->get();
        $cateInfo=Category::get();
        $c_id=getCateId($cateInfo,$cate_id);
        $data=Goods::whereIn('cate_id',$c_id)->get();

        return view('index.index',['navInfo'=>$navInfo,'data'=>$data]);
    }

}
