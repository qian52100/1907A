<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use DB;
class ProInfoController extends Controller
{
    public function proinfo(){
        $goods_id=request()->goods_id;
        $goodsInfo=Goods::find($goods_id);
        $goodsInfo['goods_imgs']=explode('|',$goodsInfo['goods_imgs']);
        return view('index.proinfo',['goodsInfo'=>$goodsInfo]);
    }
}
