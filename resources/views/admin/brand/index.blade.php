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
<h3 align="center">品牌列表</h3><a href="{{url('brand/create')}}">添加</a><hr>
<b style="color:red;">{{session('msg')}}</b>
<form action="" method="">
    <input type="text" name="brand_name" placeholder="请输入品牌名称关键字" value="{{$query['brand_name']??''}}">
    <input type="text" name="brand_url" placeholder="请输入品牌网址"value="{{$query['brand_url']??''}}">
    <button>搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌网址</th>
        <th>品牌LOGO</th>
        <th>品牌描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if($data)
     @foreach($data as $v)
    <tr brand_id="{{$v->brand_id}}">
        <td>{{$v->brand_id}}</td>
        <td field="brand_name">
            <span class="span_test" style="cursor:pointer;">{{$v->brand_name}}</span>
            <input type="text" style="display:none;" value="{{$v->brand_name}}" class="changeValue">
        </td>
        <td>{{$v->brand_url}}</td>
        <td><img src="{{env('UPLOAD_URL')}}{{$v->brand_logo}}"width="100px"></td>
        <td>{{$v->brand_desc}}</td>
        <td>
            <a href="{{url('brand/edit/'.$v->brand_id)}}" class="btn btn-info">编辑</a>
            <a href="{{url('brand/destroy/'.$v->brand_id)}}" class="btn btn-danger">删除</a>
        </td>
    </tr>
     @endforeach
     @endif
    <tr><td colspan="6">{{$data->appends($query)->links()}}</td></tr>
    </tbody>
</table>
</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(document).ready(function(){
        //即点即改
        //点击事件
        $(document).on('click','.span_test',function(){
            var _this=$(this);
            _this.hide();
            _this.next('input').show().focus();
        })
        //失去焦点事件
        $(document).on('blur','.changeValue',function(){
            var _this=$(this);
            var value=_this.val();
            var field=_this.parent('td').attr('field');
            var brand_id=_this.parents('tr').attr('brand_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('brand/changeValue')}}",
                {value:value,field:field,brand_id:brand_id},
                function(res){
                    if(res=='ok'){
                        _this.prev('span').text(value).show();
                        _this.hide();
                    }else{
                        _this.prev('span').show();
                        _this.hide();
                    }
                }
            )
        })
    })
</script>
