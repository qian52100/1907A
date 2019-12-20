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
<h3 align="center">分类列表展示</h3><a href="{{url('category/create')}}">添加</a><hr>
<b style="color:red">{{session('msg')}}</b>
<form action="" method="">
    <input type="text" name="cate_name" placeholder="分类名称关键字...." value="{{$cate_name??''}}">
    <button>搜索</button>
</form>
<table class="table table-striped">
    <thead>
    <tr>
        <th>分类ID</th>
        <th>分类名称</th>
        <th>是否展示</th>
        <th>是否导航展示</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @if($data)
     @foreach($data as $v)
    <tr cate_id="{{$v->cate_id}}" parent_id="{{$v->parent_id}}">
        <td><?php echo str_repeat("-",$v->level*5)?><a href="javascript:;" class="sign">+</a>{{$v->cate_id}}</td>
        <td field="cate_name">
            <span class="span_test" style="cursor:pointer;">{{$v->cate_name}}</span>
            <input type="text" class="changeValue" style="display:none;" value="{{$v->cate_name}}">
        </td>
        <td field="cate_show">
            <span class="changeStatus" style="cursor:pointer;">{{($v->cate_show)==1 ? '√' : '×'}}</span>
        </td>
        <td field="cate_nav_show">
            <span class="changeStatus" style="cursor:pointer;">{{($v->cate_nav_show)==1 ? '√' : '×'}}</span>
            </span>
        </td>
        <td>
            <a href="{{url('category/edit/'.$v->cate_id)}}" class="btn btn-info">编辑</a>
            <a href="{{url('category/destroy/'.$v->cate_id)}}" class="btn btn-danger">删除</a>
        </td>
    </tr>
     @endforeach
     @endif
    </tbody>
</table>
</body>
</html>
<script src="/static/jquery.js"></script>
<script>
    $(document).ready(function(){
        //顶级展示
        $("tr[parent_id]").hide();
        $("tr[parent_id=0]").show();
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
           var cate_id=_this.parents('tr').attr('cate_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post(
              "{{url('category/changeStatus')}}",
               {_value:_value,field:field,cate_id:cate_id},
              function(res){
                  if(res=='ok'){
                      _this.text(new_sign).show();
                  }
              }
          )
        })
        //点击符号
        $(document).on('click','.sign',function(){
            var _this=$(this);
            var sign=_this.text();
            var cate_id=_this.parents('tr').attr('cate_id');
            if(sign=='+'){
                if($("tr[parent_id="+cate_id+"]").length>0){
                    $("tr[parent_id="+cate_id+"]").show();
                    _this.text('-');
                }
            }else{
               // $("tr[parent_id="+cate_id+"]").hide();
                trHide(cate_id);
                _this.text('+');
            }
        })
        function trHide(cate_id){
            var _tr=$("tr[parent_id="+cate_id+"]");
            _tr.each(function(index){
                $(this).hide();
                $(this).find("a[class='sign']").text('+');
                var c_id=$(this).attr('cate_id');
                trHide(c_id);
            })
        }
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
            var _value=_this.val();
            var field=_this.parent('td').attr('field');
            var cate_id=_this.parents('tr').attr('cate_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('category/changeStatus')}}",
                {_value:_value,field:field,cate_id:cate_id},
                function(res){
                    if(res=='ok'){
                        _this.prev('span').text(_value).show();
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
