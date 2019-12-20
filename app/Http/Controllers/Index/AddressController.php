<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Area;
use App\Model\Address;
class AddressController extends Controller
{
    public function index(){
        $user_id=getUserId();
        $provinceInfo=$this->getAreaInfo(0);
        return view('index.address',['provinceInfo'=>$provinceInfo]);
    }
    //查询所有省份
    public function getAreaInfo($pid){
        $provinceInfo=Area::where('pid',$pid)->get();
        return $provinceInfo;
    }
    //查询市区
    public function getArea(){
        $id=request()->id;
        $info=$this->getAreaInfo($id);
        echo json_encode($info);
    }
    //添加收货地址
    public function add(){
        $data=request()->except('_token');
        $user_id=getUserId();
        $data['user_id']=$user_id['user_id'];
        //查询是否被设置为默认
        if(!empty($data['is_default'])){
            $where=[
                ['user_id','=',$data['user_id']],
                ['is_default','=',1]
            ];
            $res=Address::where($where)->update(['is_default'=>2]);
        }
        $info=Address::insert($data);
       if($info){
           successly('保存失败');
       }else{
           fail('保存成功');
       }
    }
    //收获地址展示
    public function list(){
        $user_id=getUserId();
        $where=[
            ['user_id','=',$user_id['user_id']],
            ['is_del','=',1]
        ];
        $info=Address::where($where)->get();
        foreach($info as $k=>$v) {
            $info[$k]['province'] = Area::where('id', $v['province'])->value('name');
            $info[$k]['city'] = Area::where('id', $v['city'])->value('name');
            $info[$k]['area'] = Area::where('id', $v['area'])->value('name');
        }
        return view('index.add_address',['info'=>$info]);
    }

}
