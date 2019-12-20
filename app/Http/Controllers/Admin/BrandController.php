<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Model\Brand;
use App\Http\Requests\StoreBlogPost;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\redis;
//use Illuminate\Support\Facades\Cache;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        //session辅助函数
//        session(['name'=>'zhangsan']); //设置session
//        request()->session()->save(); //存储到服务端
//        session(['name'=>null]);  //删除session
//        request()->session()->save(); //获取到服务端
//        $name=session('name'); //获取session
//
//        //request实例 put方法
////        request()->session()->put('age',18);  //设置session
////        request()->session()->save(); //存储到服务端
//        $age=request()->session()->get('age');  //获取session
////        $age=request()->session()->pull('age'); //获取并删除数据
////        dump($age);
////        request()->session()->forget('age');  //forget 方法从 Session 中移除指定数据  单删
//        $age=request()->session()->get('age');  //获取session
//        request()->session()->flush();
//        $name=session('name'); //获取session
//        $age=request()->session()->get('age');  //获取session

        //$data=DB::table('brand')->get();

        //ORM
        //$data=Brand::get();
        $brand_name = request()->brand_name;
        $brand_url = request()->brand_url;
        $page = request()->page;
        $where = [];
        if ($brand_name) {
            $where[] = ['brand_name', 'like', "%$brand_name%"];
        }
        if ($brand_url) {
            $where[] = ['brand_url', '=', $brand_url];
        }
        //$data = Cache::get('data_'.$page.'_'.$brand_name.'_'.$brand_url);
        $data=cache('data_'.$page.'_'.$brand_name.'_'.$brand_url);
        echo 'data_'.$page.'_'.$brand_name.'_'.$brand_url;
        if (!$data) {
            echo '走DB';
            $pageSize = config('app.pageSize');
            //监听SQL
//        DB::connection()->enableQueryLog();
            $data = Brand::where($where)->orderBy('brand_id', 'desc')->paginate($pageSize);
            //Cache::put(['data_'.$page.'_'.$brand_name.'_'.$brand_url => $data], 10);
            cache(['data_'.$page.'_'.$brand_name.'_'.$brand_url => $data], 10);
//        $data =  DB::table('brand')->where($where)->paginate($pageSize);
//        $logs = DB::getQueryLog();
        }
            $query = request()->all();
            return view('admin.brand.index', ['data' => $data, 'query' => $query]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//       $request->validate([
//            'brand_name' => 'required|unique:brand|max:12|min:2',
//            'brand_url' => 'required',
//            'brand_desc'=>'required',
//        ],
//            [
//                'brand_name.required'=>'品牌名称必填',
//                'brand_name.unique'=>'品牌名称已存在',
//                'brand_name.max'=>'品牌名称最大长度为12位',
//                'brand_name.min'=>'品牌名称最小长度为2位',
//                'brand_url.required'=>'品牌网址必填',
//            ]
        //);
       //$data=$request->all();
        //全局辅助函数 排除except  only只接收 多个字段需用数组
        $data=$request->except('_token');
        $validator = Validator::make($data, [
            'brand_name' => 'required|unique:brand|max:12|min:2',
            'brand_url' => 'required',
            'brand_desc'=>'required',
        ],
            [
                'brand_name.required'=>'品牌名称必填',
                'brand_name.unique'=>'品牌名称已存在',
                'brand_name.max'=>'品牌名称最大长度为12位',
                'brand_name.min'=>'品牌名称最小长度为2位',
                'brand_url.required'=>'品牌网址必填',
                'brand_desc.required'=>'品牌描述必填',
            ]);
        if ($validator->fails()) {
            return redirect('brand/create')
                ->withErrors($validator)
                ->withInput();
        }


        // $res=DB::table('brand')->insert($data);
        //判断有无上传
        if($request->hasFile('brand_logo')){
            $data['brand_logo']=$this->upload('brand_logo');
        }

        //ORM
        //第一种 create
        //$res=Brand::create($data);
   /**第二张 save;*/
        $brand = new Brand();
        $brand->brand_name=$data['brand_name'];
        $brand->brand_url=$data['brand_url'];
        $brand->brand_logo=$data['brand_logo']??'';
        $brand->brand_desc=$data['brand_desc'];
        $res=$brand->save();
        //第三种 insert
       // $res=Brand::insert($data);
        if($res){
           // echo "<script>alert('添加成功');location.href='/brand';</script>";
            return redirect('brand')->with('msg','添加成功');
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
        //$data=DB::table('brand')->where('brand_id',$id)->first();

        //ORM

        $data=Brand::find($id);

        return view('admin.brand.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data=$request->except('_token');
        $validator = Validator::make($data, [
            //'brand_name' => 'required|unique:brand|max:12|min:2',
            'brand_name' => [
                'required',
                Rule::unique('brand')->ignore($id,'brand_id'),
                'max:12',
                'min:2'
            ],
            'brand_url' => 'required',
            'brand_desc'=>'required',
        ],
            [
                'brand_name.required'=>'品牌名称必填',
                'brand_name.unique'=>'品牌名称已存在',
                'brand_name.max'=>'品牌名称最大长度为12位',
                'brand_name.min'=>'品牌名称最小长度为2位',
                'brand_url.required'=>'品牌网址必填',
                'brand_desc.required'=>'品牌描述必填',
            ]);
        if ($validator->fails()) {
            return redirect('brand/edit/'.$id)
                ->withErrors($validator)
                ->withInput();
        }
        //DB::table('brand')->where('brand_id',$id)->update($data);
        // ORM 第一种
        if(request()->hasFile('brand_logo')){
            $data['brand_logo']=$this->upload('brand_logo');
        }
        Brand::where('brand_id',$id)->update($data);

        //第二种
//        $brand=Brand::find($id);
//        $brand->brand_name=$data['brand_name'];
//        $brand->brand_url=$data['brand_url'];
//        $brand->brand_logo=$data['brand_logo']??'';
//        $brand->brand_desc=$data['brand_desc'];
//        $brand->save();
        echo "<script>alert('修改成功');location.href='/brand';</script>";
        //return redirect('brand');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //$res=DB::table('brand')->where('brand_id',$id)->delete();

        //ORM
        $res=Brand::destroy($id);
       if($res){
           echo "<script>alert('删除成功');location.href='/brand';</script>";
//           //return redirect('brand');
       }
    }

    //文件上传
    public function upload($brand_logo){
        if (request()->file($brand_logo)->isValid()) {
            $photo = request()->file($brand_logo);
            $store_result = $photo->store('uploads');
            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
    }

    //即点即改
    public function changeValue(){
        $value=request()->value;
        $field=request()->field;
        $brand_id=request()->brand_id;
        $arr=[$field=>$value];
        $res=Brand::where('brand_id',$brand_id)->update($arr);
        if($res){
            echo 'ok';
        }else{
            echo 'no';
        }
    }

    //验证品牌名称唯一性
    public function checkName(){
        $brand_name=request()->brand_name;
        $count=Brand::where('brand_name',$brand_name)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }

    //验证品牌名称唯一性--修改
    public function checkName1(){
        $brand_name=request()->brand_name;
        $brand_id=request()->brand_id;
        $where=[
            ['brand_id','!=',$brand_id],
            ['brand_name','=',$brand_name]
        ];
        $count=Brand::where($where)->count();
        if($count>0){
            echo 'no';
        }else{
            echo 'ok';
        }
    }
}
