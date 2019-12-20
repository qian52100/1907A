@extends('layouts.shop')
@section('title', '所有商品')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <form action="#" method="post" class="prosearch"><input type="text" /></form>
        </div>
    </header>
    <ul class="pro-select">
        <li class="pro-selCur aa" id="goods_new"><a href="javascript:;">新品</a></li>
        <li class="aa"><a href="javascript:;" id="goods_host">销量</a></li>
        <li class="aa"><a href="javascript:;" id ="goods_price">价格</a></li>
    </ul><!--pro-select/-->
    <div class="prolist">
        @foreach($goodsInfo as $v)
        <dl>
            <dt><a href="{{url('proinfo')}}?goods_id={{$v->goods_id}}"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100" height="100" /></a></dt>
            <dd>
                <h3><a href="{{url('proinfo')}}?goods_id={{$v->goods_id}}">{{$v->goods_name}}</a></h3>
                <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥599</span></div>
                <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
            </dd>
            <div class="clearfix"></div>
        </dl>
            @endforeach
    </div><!--prolist/-->
    <div class="height1"></div>
</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/static/index/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/index/js/bootstrap.min.js"></script>
<script src="/static/index/js/style.js"></script>
<!--焦点轮换-->
<script src="/static/index/js/jquery.excoloSlider.js"></script>
<script>
    $(function () {
        $("#sliderA").excoloSlider();
    });
</script>
</body>
</html>
@endsection
<script src="/static/jquery.js"></script>
<script>
    $(document).on('click','.aa',function(){
        var _this=$(this);
        _this.addClass('pro-selCur').siblings('li').removeClass('pro-selCur');
        var goods_new=$("#goods_new").text();
        var goods_host=$("#goods_host").text();
        var goods_price=$("#goods_price").text();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post(
            "{{url('/prolist/list')}}",
            {goods_new:goods_new,goods_host:goods_host,goods_price:goods_price},
            function(res){
                console.log(res)
            }
        )
    })
</script>
