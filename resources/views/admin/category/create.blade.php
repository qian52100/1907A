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
<h3 align="center">分类添加</h3><a href="{{url('category')}}">分类列表</a><hr>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{url('category/store')}}" method="post" id="myform">
    @csrf
<div class="page-content">
    <div class="row">

        <div class="col-xs-12">

            <div class="form-horizontal" role="form">
                <div class="form-group">

                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 分类名称</label>

                        <div class="col-sm-9">
                            <input type="text" placeholder="分类名称" class="col-xs-10 col-sm-5" name="cate_name" id="cate_name"/>
                            <b style="color:red">{{$errors->first('cate_name')}}</b>
                        </div>
                    </div>
                    <div class="space-4"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 是否显示 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="cate_show" value="1" checked>是
                            <input type="radio" name="cate_show" value="2">否
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 是否导航显示 </label>

                        <div class="col-sm-9">
                            <input type="radio" name="cate_nav_show" value="1">是
                            <input type="radio" name="cate_nav_show" value="2" checked>否
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label no-padding-right" for="form-field-2"> 父分类 </label>

                        <div class="col-sm-9">
                            <select name="parent_id">
                                <option value="0">--请选择--</option>
                                @foreach($data as $v)
                                <option value="{{$v->cate_id}}">{{str_repeat('-',$v->level*5)}}{{$v->cate_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-5 col-md-9">
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

                </div>
            </div>
</form>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(document).ready(function(){
        $('#cate_name').blur(function(){
            checkName();
        })
        function checkName(){
            $("#cate_name").next().text('');
            var cate_name=$("#cate_name").val();
            var reg=/^[\u4e00-\u9fa5]{2,}$/;
            if(!reg.test(cate_name)){
                $("#cate_name").next().text('分类名称由中文组成至少两位');
                return false;
            }
            return checkOnly(cate_name);
        }
        function checkOnly(cate_name){
            var flag=false;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method:"post",
                url:"{{url('category/checkName')}}",
                data:{cate_name:cate_name},
                async:false
            }).done(function(res){
                if(res=='no'){
                    $("#cate_name").next().text('分类名称已存在');
                    flag=false;
                }else{
                    flag=true;
                }
            })
            return flag;
        }
        $(document).on('click','.btn-info',function(){
            var res=checkName();
            if(res){
                $("#myform").submit();
            }
        })
    })
</script>
