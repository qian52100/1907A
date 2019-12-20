<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

//闭包函数路由  直接输出
//Route::get('/', function () {
//   echo 'welcome';
//});

//路由视图 路径 模板名 传值
//Route::view('/','welcome');

//入口文件 路由视图 路由重写
//Route::view('/hello','welcome',['welcome'=>'哈哈1907']);

//get路由
//Route::get('/test','userController@test');

//必填参数
//Route::get('/login','index\userController@login');
//
//Route::post('/dologin','index\userController@dologin')->name('do');
//正则 验证
//Route::get('/login/{id}',function($id){
//    echo $id;
//})->where('id','[0-9]+');

//可选参数 多条件
//Route::get('/login/{id?}/{name?}','index\userController@login');
//商城后台登陆模块增删改查
    Route::get('/login','Admin\LoginController@index');  //登陆
    Route::post('login/loginDo','Admin\LoginController@loginDo'); //执行登陆
    Route::post('login/dologin','Admin\LoginController@dologin'); //执行登陆

//后台商城后台的商品品牌增删该查
Route::prefix('brand')->group(function () {
    Route::get('/','Admin\BrandController@index');  //列表展示
    Route::get('create','Admin\BrandController@create'); //添加视图
    Route::post('store','Admin\BrandController@store');  //执行添加
    Route::get('edit/{id}','Admin\BrandController@edit');   //编辑视图
    Route::post('update/{id}','Admin\BrandController@update');   //编辑方法
    Route::get('destroy/{id}','Admin\BrandController@destroy');  //删除方法
    Route::post('changeValue','Admin\BrandController@changeValue'); //即点即改
    Route::post('checkName','Admin\BrandController@checkName');  //验证名称
    Route::post('checkName1','Admin\BrandController@checkName1');  //验证名称 --修改
});

//后台商城后台的商品分类增删该查
Route::prefix('category')->group(function () {
    Route::get('/','Admin\CategoryController@index');  //列表展示
    Route::get('create','Admin\CategoryController@create'); //添加视图
    Route::post('store','Admin\CategoryController@store');  //执行添加
    Route::get('edit/{id}','Admin\CategoryController@edit');   //编辑视图
    Route::post('update/{id}','Admin\CategoryController@update');   // 编辑方法
    Route::get('destroy/{id}','Admin\CategoryController@destroy');  //删除方法
    Route::post('changeStatus','Admin\CategoryController@changeStatus'); //即点即改
    Route::post('checkName','Admin\CategoryController@checkName'); //验证分类名称
    Route::post('checkName1','Admin\CategoryController@checkName1'); //验证分类名称 --修改
});

//商城后台商品模块增删改查
Route::prefix('goods')->group(function () {
    Route::get('/','Admin\GoodsController@index');  //列表展示
    Route::get('create','Admin\GoodsController@create'); //添加视图
    Route::post('store','Admin\GoodsController@store');  //执行添加
    Route::get('edit/{id}','Admin\GoodsController@edit');   //编辑视图
    Route::post('update/{id}','Admin\GoodsController@update');   //编辑方法
    Route::get('destroy/{id}','Admin\GoodsController@destroy');  //删除方法
    Route::post('changeStatus','Admin\GoodsController@changeStatus'); //点击符号
    Route::post('changeValue','Admin\GoodsController@changeValue'); //即点即改
    Route::post('checkName','Admin\GoodsController@checkName'); //验证商品名称唯一
    Route::post('checkName1','Admin\GoodsController@checkName1'); //验证商品名称唯一 --修改
});

//商城后台管理员模块增删改查
Route::prefix('admin')->group(function () {
    Route::get('/','Admin\AdminController@index'); //列表
    Route::get('create','Admin\AdminController@create'); //添加
    Route::post('store','Admin\AdminController@store');  //执行添加
    Route::get('destroy/{id}','Admin\AdminController@destroy');  //执行删除
    Route::get('edit/{id}','Admin\AdminController@edit');  //编辑
    Route::post('update/{id}','Admin\AdminController@update');  //执行编辑
    Route::post('checkAccount','Admin\AdminController@checkAccount');  //验证账号唯一
});


//三种设置cookie  先响应再输出
//Route::get('addcookie', function () {    //1
//    return response('bbb')->cookie('name', 'hahaasads', 1);
//});
//
////获取cookie
//Route::get('getcookie', function () {
//   echo  request()->cookie('name');
//});

//设置cookie 队列  先响应再输出
//Route::get('getcookie', function () {
//    //\Cookie::queue(\Cookie::make('uu', '123', 1));  2
//    \Cookie::queue('name', '1908', 1);   //3
//    //取出 Cookie两种方式
//    //return  request()->cookie('11');
//    echo \Cookie::get('name');
//
//});


//第一周内测
Route::get('/user','Admin\UserController@index');  //列表
Route::post('user/userDo','Admin\UserController@userDo'); //登陆

//文章模块
Route::prefix('article')->middleware('checkUser')->group(function () {
    Route::get('/','Admin\ArticleController@index'); //列表
    Route::get('create','Admin\ArticleController@create'); //添加
    Route::post('store','Admin\ArticleController@store'); //执行添加
    Route::post('checkTitle','Admin\ArticleController@checkTitle'); //验证标题唯一
    Route::post('destroy','Admin\ArticleController@destroy'); //删除
    Route::get('edit/{id}','Admin\ArticleController@edit'); //编辑
    Route::post('update/{id}','Admin\ArticleController@update'); //编辑
    Route::post('changeStatus','Admin\ArticleController@changeStatus'); //点击符号
});

Route::get('/','Index\IndexController@index');  //前台模板
Route::get('/reg','Index\LoginController@reg');//注册视图
Route::post('login/sendCode','Index\LoginController@sendCode');//执行注册验证码
Route::post('login/Doreg','Index\LoginController@Doreg');//执行注册

//Route::get('/login','Index\LoginController@login'); //登陆视图
//Route::post('/login/dologin','Index\LoginController@dologin'); //执行登陆

Route::get('prolist/','Index\ProlistController@prolist');//商品列表
Route::get('proinfo','Index\ProInfoController@proinfo'); //商品详情

Route::get('carlist','Index\CartController@carlist')->middleware('UserLogin'); //购物车列表
Route::post('cart/changeNum','Index\CartController@changeNum')->middleware('UserLogin'); //购物车列表  修改购买数量
Route::post('cart/changeTotal','Index\CartController@changeTotal')->middleware('UserLogin'); //购物车列表  获取小计
Route::post('cart/changeCount','Index\CartController@changeCount')->middleware('UserLogin'); //购物车列表  获取总价
Route::post('cart/del','Index\CartController@del')->middleware('UserLogin'); //购物车列表  点击删除

Route::post('/cart/addCart','Index\CartController@addcart'); //加入购物车

Route::get('order/pay','Index\OrderController@confirmOrder')->middleware('UserLogin');  //确认结算
Route::get('addOrder','Index\OrderController@addOrder')->middleware('UserLogin');  //确认结算
Route::get('order/orderSuccess','Index\OrderController@orderSuccess')->middleware('UserLogin');  //提交订单
Route::get('order/orderpay/{order_id}','Index\OrderController@orderpay')->middleware('UserLogin');  //提交订单
Route::get('return_url','Index\OrderController@return_url');  //同步跳转
Route::post('notify_url','Index\OrderController@notify_url');  //异步通知地址

Route::get('/address','Index\AddressController@index')->middleware('UserLogin');  //新增收获地址
Route::post('address/getArea','Index\AddressController@getArea')->middleware('UserLogin');  //三级联动
Route::post('address/add','Index\AddressController@add')->middleware('UserLogin');  //三级联动
Route::get('address/list','Index\AddressController@list')->middleware('UserLogin');  //收货地址展示


Route::get('user','Index\UserController@index')->middleware('UserLogin');  //我的订单视图

Route::get('/send_email','MailController@send_email');
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');


