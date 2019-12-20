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
<h3 align="center">文章编辑</h3><a href="{{url('article')}}">文章列表</a><hr>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="form-horizontal" role="form" action="{{url('article/update/'.$data->a_id)}}" method="post" enctype="multipart/form-data" id="myform">
    @csrf;
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">文章标题</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="title" id="title" value="{{$data->title}}"
                   placeholder="请输入文章标题">
            <b style="color:red;">{{$errors->first('title')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">文章分类</label>
        <div class="col-sm-10">
            <select name="c_id" id="c_id">
                <option value="">--请选择--</option>
                @foreach($cateInfo as $v)
                    <option value="{{$v->c_id}}" {{$data->c_id==$v->c_id ? 'selected' : ''}}>{{$v->c_name}}</option>
                @endforeach
            </select>
            <b style="color:red;">{{$errors->first('c_id')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">文章重要性</label>
        <div class="col-sm-10">
            <input type="radio" name="is_import" value="1" {{$data->is_import==1 ? 'checked' : ''}}>普通
            <input type="radio" name="is_import" value="2"{{$data->is_import==2 ? 'checked' : ''}}>置顶
            <b style="color:red;">{{$errors->first('is_import')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">是否显示</label>
        <div class="col-sm-10">
            <input type="radio" name="is_show" value="1" {{$data->is_show==1 ? 'checked' : ''}}>显示
            <input type="radio" name="is_show" value="2" {{$data->is_show==2 ? 'checked' : ''}}>不显示
            <b style="color:red;">{{$errors->first('is_show')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">文章作者</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="people" value="{{$data->people}}"
                   placeholder="请输入文章作者">
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">作者email</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="email"value="{{$data->email}}"
                   placeholder="请输入作者email">
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">关键字</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="keywords"
                   value="{{$data->keywords}}" placeholder="请输入关键字">
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">网页描述</label>
        <div class="col-sm-10">
            <textarea type="text" class="form-control" name="a_desc"
                      placeholder="请输入网页描述">{{$data->a_desc}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">上传文件</label>
        <div class="col-sm-10">
            <img src="{{env('UPLOAD_URL')}}{{$data->img}}" width="100px">
            <input type="file" class="form-control" name="img">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">确定</button>
            <button type="reset" class="btn btn-default">重置</button>
        </div>
    </div>
</form>
</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(function(){
        $(document).on('blur','#myform',function(){
            var res1=checkTitle();
            if(res1==false){
                return false;
            }
            return true;
        })
        function checkTitle(){
            var title=$("#title").val();
            var reg=/^[_a-z1-9\u4e00-\u9fa5]+$/;
            if(title==''){
                alert('文章标题必填');
                return false;
            }else if(!reg.test(title)){
                alert('文章标题允许中文字母数字下划线组成至少1位');
                return false;
            }else{
                var flag=false;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method:"post",
                    url:"{{url('article/checkTitle')}}",
                    data:{title:title},
                    async:false
                }).done(function(res){
                    if(res=='no'){
                        alert('标题已存在');
                        flag=false;
                    }else{
                        flag=true;
                    }
                })
                return flag;
            }
        }
    })
</script>
