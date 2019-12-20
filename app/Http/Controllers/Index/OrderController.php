<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\Goods;
use App\Model\Address;
use App\Model\Area;
use App\Model\Uorder;
use App\Model\OrderDetail;
use App\Model\OrderAddress;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //确认结算
    public function confirmOrder(){
        $goods_id=request()->goods_id;
        $goods_id=explode(',',$goods_id);
        //根据商品id 查询购物车选中商品数据
        $user_id=getUserId();
        $where=[
            ['cart_del','=',1],
            ['user_id','=',$user_id['user_id']],
        ];
        $info=Goods::join('cart','cart.goods_id','=','goods.goods_id')
            ->whereIn('cart.goods_id',$goods_id)
            ->where($where)
            ->get();
        //商品金额
        $count=0;
        foreach($info as $k=>$v){
            $count+=$v['goods_price']*$v['buy_number'];
        }
        //获取收货地址信息
        $where=[
            ['user_id','=',$user_id['user_id']],
            ['is_default','=',1]
        ];
        $addressInfo=Address::where($where)->first();
        $addressInfo['province']=Area::where('id',$addressInfo['province'])->value('name');
        $addressInfo['city']=Area::where('id',$addressInfo['city'])->value('name');
        $addressInfo['area']=Area::where('id',$addressInfo['area'])->value('name');
        return view('index.pay',['info'=>$info,'count'=>$count,'addressInfo'=>$addressInfo]);
    }
    //提交订单
    public function addOrder(){
        $goods_id=request()->goods_id;
        $goods_id=explode(',',$goods_id);
        $address_id=request()->address_id;
        $pay_type=request()->pay_type;
        DB::beginTransaction();
        try{
            if(empty($goods_id)){
                throw new \Exception('请至少选择一件商品');
            }
            //添加订单表
            $user_id=getUserId();
            $orderInfo['user_id']=$user_id['user_id'];
            $orderInfo['order_no']=time().rand(1000,9999);
            $where=[
                ['user_id','=',$user_id['user_id']],
                ['cart_del','=',1]
            ];
            $info=Cart::join('goods','cart.goods_id','=','goods.goods_id')->where($where)->whereIn('goods.goods_id',$goods_id)->get()->toArray();
            if(empty($info)){
                throw new \Exception('此订单中商品信息有误');
            }
            $count=0;
            foreach($info as $k=>$v){
                $count+=$v['goods_price']*$v['buy_number'];
            }
            $orderInfo['order_amount']=$count;
            $orderInfo['pay_type']=$pay_type;
            $orderInfo['ctime']=time();
            $res1=Uorder::insertGetId($orderInfo);
            DB::commit();
            if(empty($res1)){
                DB::rollBack();
                throw new \Exception('订单数据添加失败');
            }
            //添加收货地址表

            if(empty($address_id)){
                throw new \Exception('收货地址必须选一项');
            }
            $addressInfo=Address::where('address_id','=',$address_id)->first();
            unset($addressInfo['address_id']);
            unset($addressInfo['is_default']);
            unset($addressInfo['email']);
            unset($addressInfo['is_del']);
            if(empty($addressInfo)){
                throw new \Exception('非法操作');
            }
            $addressInfo=$addressInfo->toArray();
            $user_id=getUserId();
            $addressInfo['user_id']=$user_id['user_id'];
            $addressInfo['order_id']=$res1;
            $res2=OrderAddress::insert($addressInfo);
            DB::commit();
            if(empty($res2)){
                DB::rollBack();
                throw new \Exception('收获地址添加失败');
            }
            //添加订单商品信息表
            foreach($info as $k=>$v){
                $info[$k]['order_id']=$res1;
                $info[$k]['user_id']=$user_id['user_id'];
                unset($info[$k]['add_time']);
                unset($info[$k]['brand_id']);
                unset($info[$k]['cart_del']);
                unset($info[$k]['cate_id']);
                unset($info[$k]['cart_id']);
                unset($info[$k]['goods_best']);
                unset($info[$k]['goods_desc']);
                unset($info[$k]['goods_host']);
                unset($info[$k]['goods_imgs']);
                unset($info[$k]['goods_new']);
                unset($info[$k]['goods_no']);
                unset($info[$k]['goods_num']);
                unset($info[$k]['goods_up']);
            }
            $res3=OrderDetail::insert($info);
            DB::commit();
            if(empty($res3)){
                DB::rollBack();
               throw new \Exception('订单商品数据添加失败');
            }
            //清除购物车中已下单的商品数据
            $cartWhere=[
                ['user_id','=',$user_id['user_id']],
                ['cart_del','=',1],
            ];
            $res4=Cart::where($cartWhere)->whereIn('cart.goods_id',$goods_id)->update(['cart_del'=>2]);
            DB::commit();
            if(empty($res4)){
                //回滚事务
                DB::rollBack();
                throw new \Exception('清除购物车中已购买的商品失败');
            }
            //修改商品库存
            foreach($info as $k=>$v){
                $res5=Goods::where('goods_id',$v['goods_id'])->decrement('goods_num',$v['buy_number']);
                DB::commit();
                if(empty($res5)){
                    //回滚事务
                    DB::rollBack();
                    throw new \Exception('修改商品库存失败');
                }
            }
            echo "<script>alert('订单提交成功');location.href='/order/orderSuccess/?order_id=$res1';</script>";
        }catch(\Exception $e){
            echo $e->getMessage();
        }

    }
    public function orderSuccess(){
        $order_id=request()->order_id;
        $info=Uorder::where('order_id',$order_id)->first();
        return view('index.success',['info'=>$info]);
    }
    //支付
    public function orderpay($order_id){

        $config=config('alipay');
        $info=Uorder::where('order_id',$order_id)->first();
        require_once app_path('/lib/alipay/wappay/service/AlipayTradeService.php');
        require_once app_path('/lib/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');

        if (!empty($info->order_no)&& trim($info->order_no)!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $info->order_no;

            //订单名称，必填
            $subject = '全国最大商城';

            //付款金额，必填
            $total_amount = $info->order_amount;

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);
            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;
        }
    }
    //同步跳转
    public function return_url(){
        $config=config('alipay');
        require_once app_path('/lib/alipay/wappay/service/AlipayTradeService.php');


        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号

            $out_trade_no = htmlspecialchars($_GET['out_trade_no']);

            //支付宝交易号

            $trade_no = htmlspecialchars($_GET['trade_no']);

            echo "验证成功<br />外部订单号：".$out_trade_no;

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "验证失败";
        }
    }
    //异步通知地址
    public function notify_url(){
        $config=config('alipay');
        require_once app_path('/lib/alipay/wappay/service/AlipayTradeService.php');


        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";		//请不要修改或删除

        }else {
            //验证失败
            echo "fail";	//请不要修改或删除

        }
    }
}
