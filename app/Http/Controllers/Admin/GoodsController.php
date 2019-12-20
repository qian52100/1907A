<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods;
use App\Model\Category;
use App\Model\Brand;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class GoodsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $goods_name=request()->goods_name;
        $brand_id=request()->brand_id;
        $cate_id=request()->cate_id;
        $page=request()->page;
        $where=[];
        if($goods_name){
            $where[]=['goods_name','like',"%$goods_name%"];
        }
        if($brand_id){
            $where[]=['brand.brand_id','=',$brand_id];
        }
        //查询所有分类
        $da=Category::get();
        //获取子类的cate_id
        $c_id=getCateId($da,$cate_id);
        //每页显示条数
        $data=Cache::get('data_'.$goods_name.'_'.$brand_id.'_'.$cate_id.'_'.$page);
        echo 'data_'.$goods_name.'_'.$brand_id.'_'.$cate_id.'_'.$page;
        if(!$data){
            echo '走DB';
            $pageSize=config('app.pageSize');
            $data=Goods::join('category', 'category.cate_id', '=', 'goods.cate_id')
                ->join('brand', 'brand.brand_id', '=', 'goods.brand_id')
                ->orderBy('goods_id','desc')
                ->whereIn('goods.cate_id',$c_id)
                ->where($where)
                ->paginate($pageSize);
            foreach($data as $k=>$v){
                $data[$k]['goods_imgs']=explode('|',$v['goods_imgs']);
            }
            Cache::put(['data_'.$goods_name.'_'.$brand_id.'_'.$cate_id.'_'.$page=>$data],10);
        }
        //品牌
        $res=Brand::get();
        //分类
        $da=Category::get();
        $da=cateinfo($da);
        $query=request()->all();
        return view('admin.goods.index',['data'=>$data,'query'=>$query,'res'=>$res,'da'=>$da]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=Category::get();
        $data=cateinfo($data);
        $res=Brand::get();
        return view('admin.goods.create',['data'=>$data,'res'=>$res]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post=$request->except('_token');
        //表单验证
        $validator = Validator::make($post, [
            'goods_name' => 'required|unique:goods|max:255',
            'goods_price' => 'required',
            'goods_num' => 'required',
            'cate_id' => 'required',
            'brand_id' => 'required',
        ],[
            'goods_name.required'=>'商品名称必填',
            'goods_name.unique'=>'商品名称已存在',
            'goods_price.required'=>'商品价格必填',
            'goods_num.required'=>'商品库存必填',
            'cate_id.required'=>'分类类型必选',
            'brand_id.required'=>'品牌类型必选',

        ]);
        if ($validator->fails()) {
            return redirect('goods/create')
                ->withErrors($validator)
                ->withInput();
        }
        //判断单文件
        if($request->hasFile('goods_img')){
            $post['goods_img']=$this->img('goods_img');
        }
        //判断多文件上传
        if($request->hasFile('goods_imgs')){
            $goods_imgs=$this->img('goods_imgs');
            $post['goods_imgs']=implode('|',$goods_imgs);
        }
        $res=Goods::insert($post);
        if($res){
            echo "<script>alert('添加成功');location.href='/goods';</script>";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $da=Goods::find($id);
        $imgs=Goods::where('goods_id',$id)->value('goods_imgs');
        $imgs=explode('|',$da['goods_imgs']);
        //获取分类数据
        $data=Category::get();
        $data=cateinfo($data);
        //获取品牌数据
        $res=Brand::get();
        return view('admin.goods.edit',['da'=>$da,'data'=>$data,'res'=>$res,'imgs'=>$imgs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post=$request->except('_token');
        //表单验证
        $validator = Validator::make($post, [
            'goods_name' => [
                'required',
                Rule::unique('goods')->ignore($id,'goods_id'),
                'max:12'
            ],
            'goods_price' => 'required',
            'goods_num' => 'required',
            'cate_id' => 'required',
            'brand_id' => 'required',
        ],[
            'goods_name.required'=>'商品名称必填',
            'goods_name.unique'=>'商品名称已存在',
            'goods_price.required'=>'商品价格必填',
            'goods_num.required'=>'商品库存必填',
            'cate_id.required'=>'分类类型必填',
            'brand_id.required'=>'品牌类型必填',

        ]);
        if ($validator->fails()) {
            return redirect('goods/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }
        //判断单文件
        if($request->hasFile('goods_img')){
            $post['goods_img']=$this->img('goods_img');
        }
        //判断多文件上传
        if($request->hasFile('goods_imgs')){
            $goods_imgs=$this->img('goods_imgs');
            $post['goods_imgs']=implode('|',$goods_imgs);
        }
        Goods::where('goods_id',$id)->update($post);
        echo "<script>alert('修改成功');location.href='/goods';</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=Goods::destroy($id);
       if($res){
           echo "<script>alert('删除成功');location.href='/goods';</script>";
       }
    }

    //点击对错号
    public function changeStatus(){
        $value=request()->_value;
        $field=request()->field;
        $goods_id=request()->goods_id;
        $arr=[$field=>$value];
        $res=Goods::where('goods_id',$goods_id)->update($arr);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }

    //单双文件上传
    public function img($img){
        $photo = request()->file($img);
        if(is_array($photo)){
            $result=[];
            foreach($photo as $v){
                if($v->isValid()){
                    $result[]=$v->store('img');
                }
            }
            return $result;
        }else{
            if($photo->isValid()){
                $store_result = $photo->store('img');
                return $store_result;
            }
        }
        exit('未获取到上传文件或上传过程出错');
    }

    //验证品牌名称唯一性
    public function checkName(){
        $goods_name=request()->goods_name;
        $count=Goods::where('goods_name',$goods_name)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }

    //验证分类名称唯一 --修改
    public function checkName1(){
        $goods_name=request()->goods_name;
        $goods_id=request()->goods_id;
        $where=[
            ['goods_name','=',$goods_name],
            ['goods_id','!=',$goods_id]
        ];
        $count=Goods::where($where)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }

    //即点即改
    public function changeValue(){
        $value=request()->value;
        $field=request()->field;
        $goods_id=request()->goods_id;
        $arr=[$field=>$value];
        $res=Goods::where('goods_id',$goods_id)->update($arr);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }
}
