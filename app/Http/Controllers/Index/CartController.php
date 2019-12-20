<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\Goods;
class CartController extends Controller
{
    //加入购物车
    public function addCart(){
        $buy_number=request()->buy_number;
        $goods_id=request()->goods_id;
        if(checkLogin()){
            $result=$this->addCartDb($goods_id,$buy_number);
        }else{
            $result=$this->addCartCookie($goods_id,$buy_number);
        }
        if($result){
            successly('');
        }else{
            fail('请先登录');
        }
    }
    //加入购物车--数据库
    public function addCartDb($goods_id,$buy_number){
        $user_id=getUserId();
        $where=[
            ['goods_id','=',$goods_id],
            ['user_id','=',$user_id['user_id']],
            ['cart_del','=',1]
        ];
        $cartInfo=Cart::where($where)->first();
        if(!empty($cartInfo)){
            $result=$this->checkGoodsNum($goods_id,$buy_number,$cartInfo['buy_number']);
            if(empty($result)){
                fail('购买数量已超库存');
            }
            $res=Cart::where($where)->update(['buy_number'=>$cartInfo['buy_number']+$buy_number,'add_time'=>time()]);
        }else{
            $result=$this->checkGoodsNum($goods_id,$buy_number,$cartInfo['buy_number']);
            if(empty($result)){
                fail('购买数量已超库存');
            }
            $arr=['goods_id'=>$goods_id,'user_id'=>$user_id['user_id'],'buy_number'=>$buy_number,'add_time'=>time()];
            $res=Cart::insert($arr);
        }
        if($result){
            successly('');
        }else{
            fail('加入购物车失败');
        }
    }
    //加入购物车---cookie
    public function addCartCookie($goods_id,$buy_number){
    }


    //购物车列表
    public function carlist(){
        if(checkLogin()){
            $cartInfo=$this->carlistDb();
        }else{
            $cartInfo=$this->carListCookie();
        }
        $user_id=getUserId();
        $where=[
            ['cart_del','=',1],
            ['user_id','=',$user_id['user_id']]
        ];
        $count=Cart::join('goods','goods.goods_id','=','cart.goods_id')->where($where)->orderBy('add_time','desc')->count();
        return view('index.car',['cartInfo'=>$cartInfo,'count'=>$count]);
    }
    //检测库存
    public function checkGoodsNum($goods_id,$buy_number,$alreay_num=0)
    {
        //根据商品id 查询到此商品库存
        $goods_num = Goods::where('goods_id', $goods_id)->value('goods_num');
        if (($buy_number + $alreay_num) <= $goods_num) {
            return true;
        } else {
            return false;
        }
    }
    //购物车列表--数据库
    public function carlistDb(){
        $user_id=getUserId();
        $where=[
            ['cart_del','=',1],
            ['user_id','=',$user_id['user_id']]
        ];
        $cartInfo=Cart::join('goods','goods.goods_id','=','cart.goods_id')->where($where)->orderBy('add_time','desc')->get();
        return $cartInfo;
    }
    //购物车列表--cookie
    public function carListCookie(){

    }

    //购物车列表--修改购买数量
    public function changeNum(){
        $goods_id=request()->goods_id;
        $buy_number=request()->buy_number;
        if(checkLogin()){
            $result=$this->changeNumDb($goods_id,$buy_number);
        }else{
            $result=$this->changeNumCookie($goods_id,$buy_number);
        }
        if($result!==false){
            echo 'ok';
        }else{
            echo 'no';
        }
    }
    //修改购买数量--数据库
    public function changeNumDb($goods_id,$buy_number){
        $user_id=getUserId();
        $where=[
          ['goods_id','=',$goods_id],
          ['cart_del','=',1],
          ['user_id','=',$user_id['user_id']]
        ];
        return Cart::where($where)->update(['buy_number'=>$buy_number]);
    }
    //获取小计
    public function changeTotal(){
        $goods_id=request()->goods_id;
        $goods_price=Goods::where('goods_id',$goods_id)->value('goods_price');
        if(checkLogin()){
            $result=$this->changeTotalDb($goods_id,$goods_price);
        }else{
            $result=$this->changeTotalCookie($goods_id,$goods_price);
        }
        echo $result;
    }
    //获取小计 --数据库
    public function changeTotalDb($goods_id,$goods_price){
        $user_id=getUserId();
        $where=[
            ['goods_id','=',$goods_id],
            ['cart_del','=',1],
            ['user_id','=',$user_id['user_id']]
        ];
        $buy_number=Cart::where($where)->value('buy_number');
        return $buy_number*$goods_price;
    }
    //获取小计--cookie
    public function changeTotalCookie($goods_id,$goods_price){

    }
    //获取总价
    public function changeCount(){
        $goods_id=request()->goods_id;
        if(checkLogin()){
            $result=$this->changeCountDb($goods_id);
        }else{
            $result=$this->changeCountCookie($goods_id);
        }
        echo $result;
    }
    //获取总价--数据库
    public function changeCountDb($goods_id){
        $goods_id=explode(',',$goods_id);
        $user_id=getUserId();
        $where=[
            ['cart_del','=',1],
            ['user_id','=',$user_id['user_id']]
        ];
        //dd($where);
        $goodsInfo=Cart::leftjoin('goods as g','g.goods_id','=','cart.goods_id')
            ->whereIn('cart.goods_id',$goods_id)
            ->where($where)
            ->get();
        $int = 0;
        foreach($goodsInfo as $k=>$v){
          $int+=($v->buy_number*$v->goods_price);
       }
      return $int;
    }
    //获取总价--cookie
    public function changeCountCookie($goods_id){

    }
    //点击删除
    public function del(){
        $goods_id=request()->goods_id;
        if(checkLogin()){
            $result=$this->delDb($goods_id);
        }else{
            $result=$this->delCookie($goods_id);
        }
        if($result) {
            successly('');
        }else{
            fail('删除失败');
        }
    }
    //点击删除--数据库
    public function delDb($goods_id){
        $user_id=getUserId();
        $goods_id=explode(',',$goods_id);
        $where=[
            ['user_id','=',$user_id['user_id']],
            ['cart_del','=',1]
        ];
        return Cart::where($where)->whereIn('goods_id',$goods_id)->update(['cart_del'=>2]);
    }












}
