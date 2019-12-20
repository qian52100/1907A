@extends('layouts.shop')
@section('title', '收货地址')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>收货地址</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="/static/index/images/head.jpg" />
    </div><!--head-top/-->
    <form action="login.html" method="get" class="reg-login" id="myform">
        @csrf
        <div class="lrBox">
            <div class="lrList"><input type="text" placeholder="收货人" name="man"/></div>
            <div class="lrList"><input type="text" placeholder="详细地址" name="detail_add" /></div>
            <div class="lrLis">
                <select name="province" class=" area">
                    <option value="0" selected="selected">--请选择--</option>
                    @foreach($provinceInfo as $v)
                    <option value="{{$v->id}}">{{$v->name}}</option>
                        @endforeach
                </select>
                <select name="city" class="area">
                    <option value="0">--请选择--</option>
                </select>
                <select class="area" name="area">
                    <option value="">--请选择--</option>
                </select>
            </div>
            <div class="lrList"><input type="text" placeholder="手机" name="phone" /></div>
            <div class="lrList2"><input name="is_default" value="1" type="checkbox">设为默认</div>
        </div><!--lrBox/-->
        <div class="lrSub">
            <input type="button"  class='add' value="保存" />
        </div>
    </form><!--reg-login/-->

    <div class="height1"></div>

</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/static/index/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/index/js/bootstrap.min.js"></script>
<script src="/static/index/js/style.js"></script>
<!--jq加减-->
<script src="/static/index/js/jquery.spinner.js"></script>
<script>
    $('.spinnerExample').spinner({});
</script>
</body>
</html>
@endsection
<script src="/static/jquery.js"></script>
<script>
    $(function(){
        //给下拉菜单绑定内容改变事件
        $(document).on('change','.area',function(){
            var _this=$(this);
            _this.nextAll("select").html("<option value=''>--请选择--</option>");
            //获取下拉菜单的值 (id)
            var id=_this.val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('address/getArea')}}",
                {id:id},
                function(res){
                    //Ajax的回调函数 把响应回来的数据处理下拉菜单选项(_option)
                    var _option="<option value=''>--请选择--</option>";
                    for(var i in res){
                        _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                    }
                    //把处理好的_option替换掉当前下拉菜单的下一个兄弟节点
                    _this.next('select').html(_option);
                },
                'json'
            )
        })
        //点击添加
        $(document).on('click','.add',function(){
            var data=$("#myform").serialize();
            $.post(
                "{{url('address/add')}}",
                 data,
                function(res) {
                    if (res.code == 1) {
                        location.href="{{url('carlist')}}";
                    } else {
                        alert(res.font)
                    }
                },
                'json'
            )
        })
    })

</script>
