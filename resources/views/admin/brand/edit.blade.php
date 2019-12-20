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
<h3 align="center">品牌编辑</h3><a href="{{url('brand')}}">列表展示</a><hr>
<form class="form-horizontal" role="form" action="{{url('brand/update/'.$data->brand_id)}}" method="post"enctype="multipart/form-data" id="myform">
    @csrf;
    <input type="hidden" name="brand_id" value="{{$data->brand_id}}">
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">品牌名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="brand_name" id="brand_name"
                   placeholder="请输入品牌名称" value="{{$data->brand_name}}">
            <b style="color:red;">{{$errors->first('brand_name')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌网址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="brand_url" id="brand_url"
                   placeholder="请输入网址" value="{{$data->brand_url}}">
            <b style="color:red;">{{$errors->first('brand_url')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌LOGO</label>
        <div class="col-sm-10">
            <img src="{{env('UPLOAD_URL')}}{{$data->brand_logo}}"width="100px">
            <input type="file" class="form-control" name="brand_logo" id="lastname"
                   placeholder="请输入品牌logo">
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌描述</label>
        <div class="col-sm-10">
            <textarea type="text" class="form-control" name="brand_desc" id="lastname"
                      placeholder="请输入品牌描述">{{$data->brand_desc}}</textarea>
            <b style="color:red;">{{$errors->first('brand_desc')}}</b>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-default">编辑</button>
        </div>
    </div>
</form>
</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $("#brand_name").blur(function(){
        checkName();
    })

    $('input[name="brand_url"]').blur(function(){
        checkUrl();
    })
    $(".btn-default").click(function(){
        var  res1=checkName();
         var res2=checkUrl();
        if(res1&&res2){
            $(".form-horizontal").submit();
        }
    })
    function checkName(){
        $("#brand_name").next().text('');
        var brand_name= $("#brand_name").val();
        var reg=/^[\u4e00-\u9fa5\w]{2,12}$/;
        if(!reg.test(brand_name)){
            $("#brand_name").next().text('品牌名称允许中文数字字母下划线组成2-12位');
            return false;
        }
        return checkOnly(brand_name);
    }
    function checkUrl(){
        $('input[name="brand_url"]').next().text('');
        var brand_url=$('input[name="brand_url"]').val();
        var reg=/^http:\/\/*/;
        if(!reg.test(brand_url)){
            $('input[name="brand_url"]').next().text('网址需以http://开头');
            return false;
        }
        return true;
    }
    function checkOnly(brand_name){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var brand_id="{{$data->brand_id}}";
        var flag=true;
        $.ajax({
            method:"post",
            url:"{{url('brand/checkName1')}}",
            async:false,
            data:{brand_name:brand_name,brand_id:brand_id}
        }).done(function(res){
            if(res=='no'){
                $("#brand_name").next().text('品牌名称已存在');
                flag=false;
            }
        })
        return flag;
    }
</script>
