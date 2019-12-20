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
<h3 align="center">商品列表</h3><a href="{{url('goods/create')}}">商品添加</a><hr>
<form action="" method="">
    <input type="text" name="goods_name" placeholder="商品名称关键字" value="{{$query['goods_name']??''}}">
    <select name="brand_id">
        <option value="">--请选择--</option>
        @foreach($res as $v)
            <option value="{{$v->brand_id}}" @if(isset($query['brand_id'])&&$v->brand_id==$query['brand_id']) selected @endif>{{$v->brand_name}}</option>
        @endforeach
    </select>
    <select name="cate_id">
        <option value="">--请选择--</option>
        @foreach($da as $v)
            <option value="{{$v->cate_id}}" @if(isset($query['cate_id'])&&$v->cate_id==$query['cate_id']) selected @endif>{{str_repeat("-",$v->level*4)}}{{$v->cate_name}}</option>
        @endforeach
    </select>
    <button>搜索</button>
</form><hr>
<table class="table table-hover">
    <thead>
    <tr>
        <th>商品ID</th>
        <th>商品名称</th>
        <th>商品价格</th>
        <th>商品库存</th>
        <th>商品图片</th>
        <th>商品相册</th>
        <th>是否上架</th>
        <th>是否新品</th>
        <th>是否精品</th>
        <th>是否热卖</th>
        <th>品牌类型</th>
        <th>分类类型</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if($data)
        @foreach($data as $v)
            <tr class="active" goods_id="{{$v->goods_id}}">
                <td>{{$v->goods_id}}</td>
                <td field="goods_name">
                    <span class="span_test" style="cursor:pointer;">{{$v->goods_name}}</span>
                    <input type="text" value="{{$v->goods_name}}" class="changeValue" style="display:none;">
                </td>
                <td>{{$v->goods_price}}</td>
                <td>{{$v->goods_num}}</td>
                <td><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100px"></td>
                <td>
                   @foreach($v->goods_imgs as $vv)
                        <img src="{{env('UPLOAD_URL')}}{{$vv}}" width="100px">
                       @endforeach
                </td>
                <td field="goods_up">
                    <span class="changeStatus" style="cursor:pointer;">{!! ($v->goods_up)==1 ? "<font color='green'>√</font>" : "<font color='red'>×</font>" !!}</span>
                </td>
                <td field="goods_new">
                    <span class="changeStatus" style="cursor:pointer;">{!! ($v->goods_new)==1 ? "<font color='green'>√</font>" : "<font color='red'>×</font>" !!}</span>
                </td>
                <td field="goods_best">
                    <span class="changeStatus" style="cursor:pointer;">{!! ($v->goods_best)==1 ? "<font color='green'>√</font>" : "<font color='red'>×</font>" !!}</span>
                </td>
                <td field="goods_host">
                <span class="changeStatus" style="cursor:pointer;">{!!($v->goods_host)==1 ? "<font color='green'>√</font>" : "<font color='red'>×</font>" !!}</span>
                </td>
                <td>{{$v->brand_name}}</td>
                <td>{{$v->cate_name}}</td>
                <td>
                    <a href="{{url('goods/edit/'.$v->goods_id)}}" class="btn btn-primary">编辑</a>
                    <a href="{{url('goods/destroy/'.$v->goods_id)}}" class="btn btn-danger">删除</a>
                </td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td colspan="13">{{$data->appends($query)->links()}}</td>
    </tr>
    </tbody>
</table>

</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(function(){
        //点击对错号
        $(document).on('click','.changeStatus',function(){
            var _this=$(this);
            var sign=_this.text();
            if(sign=='√'){
                var new_sign='×';
                var _value=2;
            }else{
                var new_sign='√';
                var _value=1;
            }
            var field=_this.parent('td').attr('field');
            var goods_id=_this.parents('tr').attr('goods_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('goods/changeStatus')}}",
                {_value:_value,field:field,goods_id:goods_id},
                function(res){
                    if(res=='ok'){
                        _this.text(new_sign).show();
                    }
                }
            )
        })
        //即点即改
        $(document).on('click','.span_test',function(){
            var _this=$(this);
            _this.hide();
            _this.next().show().focus();
        })
        //失去焦点事件
        $(document).on('blur','.changeValue',function(){
            var _this=$(this);
            var value=_this.val();
            var field=_this.parent().attr('field');
            var goods_id=_this.parents('tr').attr('goods_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('goods/changeValue')}}",
                {value:value,field:field,goods_id:goods_id},
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



