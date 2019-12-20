<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Cate;
use App\Model\Article;
use Validator;
use Illuminate\Validation\Rule;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title=request()->title;
        $c_id=request()->c_id;
        $where=[];
        if($title){
            $where[]=['title','like',"%$title%"];
        }
        if($c_id){
            $where[]=['cate.c_id','=',$c_id];
        }
        $cateInfo=Cate::get();
        $pageSize=config('app.pageSize');
        $data=Article::leftjoin('cate', 'cate.c_id', '=', 'article.c_id')
            ->orderBy('a_id','desc')
            ->where($where)
            ->paginate($pageSize);
        $query=request()->all();
        return view('admin.article.index',['data'=>$data,'cateInfo'=>$cateInfo,'query'=>$query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cateInfo=Cate::get();
        return view('admin.article.create',['cateInfo'=>$cateInfo]);
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
        if($request->hasFile('img')){
            $data['img']=$this->img('img');
        }
        $validator = Validator::make($data, [
            'title' => 'required|unique:article|regex:/^[_a-z0-9\x{4e00}-\x{9fa5}]+$/u',
            'c_id' => 'required',
            'is_import' => 'required',
            'is_show' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('article/create')
                ->withErrors($validator)
                ->withInput();
        }
        $data['create_time']=time();
        $res=Article::insert($data);
        if($res){
            echo "<script>alert('添加成功');location.href='/article';</script>";
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
        $cateInfo=Cate::get();
        $data=Article::find($id);
        if($data){
            return view('admin.article.edit',['data'=>$data,'cateInfo'=>$cateInfo]);
        }
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
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                Rule::unique('article')->ignore($id,'a_id'),
                'regex:/^[_a-z0-9\x{4e00}-\x{9fa5}]+$/u'
            ],
            'is_import' => 'required',
            'is_show' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('article/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }
        if($request->hasFile('img')){
            $data['img']=$this->img('img');
        }
        Article::where('a_id',$id)->update($data);
        echo "<script>alert('编辑成功');location.href='/article';</script>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $a_id=request()->a_id;
        $res=Article::destroy($a_id);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }
    //文件上传
    public function img($img){
        if (request()->file($img)->isValid()) {
            $photo = request()->file($img);
            $store_result = $photo->store('img');
            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
    }

    //验证标题唯一
    public function checkTitle(){
        $title=request()->title;
        $count=Article::where('title',$title)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }

    //点击符号
    public function changeStatus(){
        $value=request()->value;
        $field=request()->field;
        $a_id=request()->a_id;
        $arr=[$field=>$value];
        $res=Article::where('a_id',$a_id)->update($arr);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }
}
