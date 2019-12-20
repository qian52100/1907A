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
</head>
<body>
<h3 align="center">管理员列表</h3><a href="{{url('admin/create')}}">管理员添加</a>
<table class="table table-hover">
    <thead>
    <tr>
        <th>管理员ID</th>
        <th>管理员账号</th>
        <th>头像</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if($data)
     @foreach($data as $v)
    <tr>
        <td>{{$v->id}}</td>
        <td>{{$v->account}}</td>
        <td><img src="{{env('UPLOAD_URL')}}{{$v->photo}}" width="100px"></td>
        <td>
            <a href="{{url('admin/edit/'.$v->id)}}" class="btn btn-info">编辑</a>
            <a href="{{url('admin/destroy/'.$v->id)}}" class="btn btn-danger">删除</a>
        </td>
        @endforeach
        @endif
    </tr>
        <tr>
            <td colspan="4">{{$data->links()}}</td>
        </tr>
    </tbody>
</body>
</html>
