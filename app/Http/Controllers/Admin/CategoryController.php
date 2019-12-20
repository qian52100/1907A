<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cate_name=request()->cate_name;
        $where=[];
        if($cate_name){
            $where[]=['cate_name','like',"%$cate_name%"];
        }
        $data=Category::where($where)->get();
        $data=cateinfo($data);
        return view('admin.category.index',['data'=>$data,'cate_name'=>$cate_name]);
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
        return view('admin.category.create',['data'=>$data]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data=$request->except('_token');
        $validator = Validator::make($data, [
            'cate_name' => 'required|unique:category|max:12',
        ],[
            'cate_name.required'=>'分类名称必填',
            'cate_name.unique'=>'分类已存在',
        ]);
        if ($validator->fails()) {
            return redirect('category/create')
                ->withErrors($validator)
                ->withInput();
        }

        $res=Category::insert($data);
        if($res){
            return redirect('category')->with(['msg'=>'添加成功']);
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
        $res=Category::find($id);
        $data=Category::all();
        $data=cateinfo($data);
        return view('admin.category.edit',['data'=>$data,'res'=>$res]);
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
        $data=$request->except('_token');
        $validator = Validator::make($data, [
            'cate_name' => [
                'required',
                Rule::unique('category')->ignore($id,'cate_id'),
                'max:12'
            ],
        ],[
            'cate_name.required'=>'分类名称必填',
            'cate_name.unique'=>'分类已存在',
        ]);
        if ($validator->fails()) {
            return redirect('category/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }
        Category::where('cate_id',$id)->update($data);
        echo "<script>alert('编辑成功');location.href='/category';</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=Category::destroy($id);
        if($res){
            echo "<script>alert('删除成功');location.href='/category';</script>";
            //return redirect('category')->with(['msg'=>'删除成功']);
        }
    }

    //即点即改
    public function changeStatus( Request $request){
        $value = request()->_value;
        $field=request()->field;
        $cate_id=request()->cate_id;
        $arr=[$field=>$value];
        $res=Category::where('cate_id',$cate_id)->update($arr);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }

    //验证分类名称唯一性
    public function checkName(){
        $cate_name=request()->cate_name;
        $count=Category::where('cate_name',$cate_name)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }

    //验证分类名称唯一 --修改
    public function checkName1(){
        $cate_name=request()->cate_name;
        $cate_id=request()->cate_id;
        $where=[
            ['cate_name','=',$cate_name],
            ['cate_id','!=',$cate_id]
        ];
        $count=Category::where($where)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }
}
