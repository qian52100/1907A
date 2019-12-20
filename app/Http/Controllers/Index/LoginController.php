<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Users;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(){
        return view('index.login');
    }
    public function reg(){
        return view('index.reg');
    }
    //执行注册
    public function Doreg(){
        $data=request()->except('_token');
        $code = session('phone'); //获取验证码  823810
        if(empty($code)){
           fail('请先获取验证码');
        }else if($code['code']!=$data['user_code']){
            fail('验证码有误');
        }
        //验证密码
        if(empty($data['user_pwd'])){
            fail('密码必填');
        }else if($data['user_pwd']!==$data['user_pwd1']){
            fail('确认密码必须和密码一致');
        }
        $users = new Users();
        $users->user_pwd=bcrypt($data['user_pwd']);
        $users->user_code=$data['user_code'];
        $users->user_tel=$data['user_tel'];
        $users->create_time=time();
        $result=$users->save();
        if($result){
           successly('注册成功');
        }else{
            fail('注册失败');
        }

    }
    //发送验证码
    public function sendCode(){
        $tel=request()->account;
        //生成验证码==随机生成一个六位随机数  rand(100000,999999)
        $code=rand(100000,999999);
        //发短信
        //$res = $this->spendtel($tel,$code);
        $res=true;
        if($res){
            session(['phone'=>['tel'=>$tel,'code'=>$code]]);
            request()->session()->save();
            successly('发送成功');
        }else{
            fail('发送失败');
        }
    }

    //执行登陆
    public function dologin(){
        $account=request()->account;
        $user_pwd=request()->user_pwd;

        //dd($account);
        //验证是否是邮箱格式 检测一个字符串在另一个字符串中出现的次数
        /*if(substr_count($account,'@')>0){
            $where[]=['user_email','=',$account];//邮箱格式
        }else{
            $where[]=['user_tel','=',$account];//手机号
        }*/
        //查询账号
        $userInfo=Users::where('user_tel','=',$account)->first();
        if($userInfo){
            if (Hash::check($user_pwd, $userInfo['user_pwd'])) {
                request()->session()->put('userInfo', ['user_id' => $userInfo['user_id']]);//设置
                request()->session()->save();
                //登陆成功
                echo "<script>alert('登陆成功');location.href='/';</script>";die;
            }
        }
    }

    //发送手机短信
  function spendtel($tel,$code){
        AlibabaCloud::accessKeyClient('LTAI4FkzwcdRm9busAzqaSeK', 'Y2dcwZLCzoevi04nOYs3rh6rS3GL3l')
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => "$tel",
                        'SignName' => "admin123",
                        'TemplateCode' => "SMS_176520819",
                        'TemplateParam' => "{code:$code}",
                    ],
                ])
                ->request();
          //  print_r($result->toArray());
        } catch (ClientException $e) {
           // echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            //echo $e->getErrorMessage() . PHP_EOL;
        }
        return true;
    }
}

