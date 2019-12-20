<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Category;
class ProlistController extends Controller
{
    public function prolist(){
        $id=request()->cate_id;
        $cateInfo=Category::get();
        $c_id=getCateId($cateInfo,$id);
        $goodsInfo = Goods::whereIn('cate_id',$c_id)->get();
        return view('index.prolist',['goodsInfo'=>$goodsInfo]);
    }
}
