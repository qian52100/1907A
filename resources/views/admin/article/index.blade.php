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
<h3 align="center">文章列表</h3><a href="{{url('article/create')}}">文章添加</a><hr>
<form action="" method="">
    <input type="text" name="title" placeholder="请输入标题关键字" value="{{$query['title']??''}}" >
    <select name="c_id">
        <option value="">--请选择--</option>
        @foreach($cateInfo as $v)
            <option value="{{$v->c_id}}" @if(isset($query['c_id'])&&$v->c_id==$query['c_id']) selected @endif>{{$v->c_name}}</option>
        @endforeach
    </select>
    <button>搜索</button>
</form>
<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>文章标题</th>
        <th>文章分类</th>
        <th>文章重要性</th>
        <th>是否显示</th>
        <th>添加日期</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if($data)
        @foreach($data as $v)
            <tr a_id="{{$v->a_id}}">
                <td>{{$v->a_id}}</td>
                <td>{{$v->title}}</td>
                <td>{{$v->c_name}}</td>
                <td>{{$v->is_import==1 ? '普通' : '置顶'}}</td>
                <td field="is_show">
                    <span class="changeStatus" style="cursor:pointer;">{{$v->is_show==1 ? '√' : '×'}}</span>
                </td>
                <td>{{date("Y-m-d H:00",$v->create_time)}}</td>
                <td>
                    <a href="{{url('article/edit/'.$v->a_id)}}" class="btn btn-info">编辑</a>
                    <a href="javascript:;" class="btn btn-danger del">删除</a>
                </td>
            </tr>
        @endforeach
    @endif
    <tr><td colspan="7">{{$data->appends($query)->links()}}</td></tr>
    </tbody>
</table>
</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(function(){
        $(document).on('click','.del',function(){
            var _this=$(this);
            var a_id=_this.parents('tr').attr('a_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('article/destroy')}}",
                {a_id:a_id},
                function(res){
                    if(res=='ok'){
                        _this.parents('tr').remove();
                    }else{
                        alert('删除失败');
                    }
                }
            )
        })
        $(document).on('click','.changeStatus',function(){
            var _this=$(this);
            var sign=_this.text();
            if(sign=='√'){
                var new_sign='×';
                var value=2;
            }else{
                var new_sign='√';
                var value=1;
            }
            var field=_this.parent('td').attr('field');
            var a_id=_this.parents('tr').attr('a_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('article/changeStatus')}}",
                {value:value,field:field,a_id:a_id},
                function(res){
                    if(res=='ok'){
                        _this.text(new_sign).show();
                    }
                }
            )
        })
    })
</script>
