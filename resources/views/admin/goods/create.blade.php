<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/static/admin/css/bootstrap.min.css">
    <script src="/static/admin/js/jquery.js"></script>
    <script src="/static/admin/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="main-content">

    <div class="page-content">
        <div class="row">


            <div class="col-xs-12">
                <h3 align="center">商品添加</h3><a href="{{url('goods')}}">商品列表</a>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post" action="{{url('goods/store')}}" >

                    @csrf
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 商品名称</label>

                        <div class="col-sm-9">
                            <input type="text" id="goods_name" placeholder="商品名称"  name="goods_name" />
                            <b style="color:red">{{$errors->first('goods_name')}}</b>
                        </div>
                    </div>

                    <div class="space-4"></div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">商品价格</label>

                        <div class="col-sm-9">
                            <input type="text" id="goods_price" placeholder="商品价格"  name="goods_price"/>
                            <b style="color:red">{{$errors->first('goods_price')}}</b>
                        </div>
                    </div>

                    <div class="space-4"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">库存数量</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-3" placeholder="库存数量" name="goods_num" />
                            <b style="color:red">{{$errors->first('goods_num')}}</b>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">商品积分</label>

                        <div class="col-sm-9">
                            <input type="text" id="form-field-5"  placeholder="商品积分" name="goods_score" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">商品图片 </label>

                        <div class="col-sm-9">
                            <input type="file" id="form-field-6"  class="col-xs-10 col-sm-5" name="goods_img"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 商品相册</label>

                        <div class="col-sm-9">
                            <input type="file" class="col-xs-10 col-sm-3" name="goods_imgs[]" multiple/>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 商品详情 </label>

                        <div class="col-sm-9">
                            <textarea name="goods_desc" id="editor"></textarea>

                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否上架 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="goods_up" value="1" checked>是
                            <input type="radio" name="goods_up" value="2">否
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否新品 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="goods_new" value="1" checked>是
                            <input type="radio" name="goods_new" value="2">否
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否精品 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="goods_best" value="1" checked>是
                            <input type="radio" name="goods_best" value="2">否
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否热卖 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="goods_host" value="1" checked>是
                            <input type="radio" name="goods_host" value="2">否
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">品牌</label>

                        <div class="col-sm-9">
                            <select name="brand_id">
                                <option value="">--请选择--</option>
                                @foreach($res as $v)
                                <option value="{{$v->brand_id}}">{{$v->brand_name}}</option>
                                @endforeach
                            </select>
                            <b style="color:red">{{$errors->first('brand_id')}}</b>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2">分类</label>

                        <div class="col-sm-9">
                            <select name="cate_id">
                                <option value="">--请选择--</option>
                               @foreach($data as $v)
                                <option value="{{$v->cate_id}}">{{str_repeat("--",$v->level*5)}}{{$v->cate_name}}</option>
                               @endforeach
                            </select>
                            <b style="color:red">{{$errors->first('cate_id')}}</b>
                        </div>
                    </div>


                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="button">
                                <i class="icon-ok bigger-110"></i>
                                增加
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="icon-undo bigger-110"></i>
                                重置
                            </button>
                        </div>
                    </div>

                    <div class="hr hr-24"></div>



                </form>
            </div><!-- /span -->
        </div><!-- /row -->

    </div><!-- /.page-content --></div><!-- /.main-content -->



</body>
</html>
<script src="/static/jquery.js"></script>
<script>
$(function(){
    $("#goods_name").blur(function(){
        checkName();
    })
    $(".btn-info").click(function(){
        var res=checkName();
        if(res){
            $(".form-horizontal").submit();
        }
    })
    function checkName(){
        $("#goods_name").next().text('');
        var goods_name=$("#goods_name").val();
        var reg=/^[\u4e00-\u9fa5\d]{2,12}$/;
        if(!reg.test(goods_name)){
            $("#goods_name").next().text('商品名称允许数字中文组成2-12位');
        }else{
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var flag=true;
            $.ajax({
                method:"post",
                url:"{{url('goods/checkName')}}",
                data:{goods_name:goods_name},
                async:false
            }).done(function(res){
                if(res=='no'){
                    $("#goods_name").next().text('商品名称已存在');
                    flag=false;
                }
            })
            return flag;
        }
    }
})
</script>
