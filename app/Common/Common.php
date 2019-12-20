<?php
//无限极分类
function cateinfo($data,$parent_id=0,$level=0){
    static $newArray=[];
    foreach($data as $v){
        if($v->parent_id==$parent_id){
            $v->level=$level;
            $newArray[]=$v;
            cateinfo($data,$v->cate_id,$v->level+1);
        }
    }
    return $newArray;
}

//查询cate_id
function getCateId($cateinfo,$parent_id){
    static $c_id=[];
    $c_id[$parent_id]=$parent_id;
    foreach($cateinfo as $k=>$v){
        if($v['parent_id']==$parent_id){
            $c_id[$v['cate_id']]=$v['cate_id'];
            getCateId($cateinfo,$v['cate_id']);
        }
    }
    return $c_id;
}
//检测用户是否登陆
function checkLogin(){
    return request()->session()->get('userInfo');
}
//获取用户id
function getUserId(){
    return request()->session()->get('userInfo');
}
function fail($font){
    $arr=['font'=>$font,'code'=>2];
    echo json_encode($arr);exit;
}
function successly($font){
    $arr=['font'=>$font,'code'=>1];
    echo json_encode($arr);exit;
}

