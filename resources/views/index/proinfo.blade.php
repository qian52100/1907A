<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" contect="http://www.webqin.net">
    <title>全国最大批发商 - @yield('商品详情')</title>
    <link rel="shortcut icon" href="images/favicon.ico" />

    <!-- Bootstrap -->
    <link href="/static/index/css/bootstrap.min.css" rel="stylesheet">
    <link href="/static/index/css/style.css" rel="stylesheet">
    <link href="/static/index/css/response.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<form class="maincont">
    @csrf
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>产品详情</h1>
        </div>
    </header>
    <div id="sliderA" class="slider">
        @foreach($goodsInfo->goods_imgs as $v)
            <img src="{{env('UPLOAD_URL')}}{{$v}}" />
        @endforeach
    </div><!--sliderA/-->
    <table class="jia-len">
        <tr goods_num="{{$goodsInfo->goods_num}}">
            <th><strong class="orange">￥{{$goodsInfo->goods_price}}</strong></th>
            <td>
                <span><a id="add">+</a></span>
                <input type="text" id="buy_number" value="1" style="width:50px; height=40px">
                <span><a id="less">-</a></span>
            </td>
        </tr>
        <tr>
            <td>
                <strong>{{$goodsInfo->goods_name}}</strong>
            </td>
            <td align="right">
                <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty"></span></a>
            </td>
        </tr>
    </table>
    <div class="height2"></div>
    <h3 class="proTitle">商品规格</h3>
    <ul class="guige">
        <li class="guigeCur"><a href="javascript:;">50ML</a></li>
        <li><a href="javascript:;">100ML</a></li>
        <li><a href="javascript:;">150ML</a></li>
        <li><a href="javascript:;">200ML</a></li>
        <li><a href="javascript:;">300ML</a></li>
        <div class="clearfix"></div>
    </ul><!--guige/-->
    <div class="height2"></div>
    <div class="zhaieq">
        <a href="javascript:;" class="zhaiCur">商品简介</a>
        <a href="javascript:;">商品参数</a>
        <a href="javascript:;" style="background:none;">订购列表</a>
        <div class="clearfix"></div>
    </div><!--zhaieq/-->
    <div class="proinfoList">
        <img src="{{env('UPLOAD_URL')}}{{$goodsInfo->goods_img}}" width="636" height="822" />
    </div><!--proinfoList/-->
    <div class="proinfoList">
        暂无信息....
    </div><!--proinfoList/-->
    <div class="proinfoList">
        暂无信息......
    </div><!--proinfoList/-->
    <table class="jrgwc">
        <tr>
            <th>
                <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
            </th>
            <td><a href="javascript:;" id="addCart">加入购物车</a></td>
        </tr>
    </table>
</form>
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
<!--jq加减-->
<script src="/static/index/js/jquery.spinner.js"></script>
<script>
    $('.spinnerExample').spinner({});
</script>
</body>
</html>
<sccipt src="/static/jquery.js"></sccipt>
<script>
    $(function(){
        $(document).on('click','#add',function(){
            var buy_number=parseInt($("#buy_number").val());
            var goods_num=parseInt($(this).parents('tr').attr('goods_num'));//因为从html获取到的值是字符串类型所以需要将其转换成数值使用parseInt
            if(buy_number>=goods_num){
                //如果文本框值大于库存值
                $("#buy_number").val(goods_num);//将文本框中的值改为库存值

            }else{
                buy_number+=1;//否则需要每次文本框值加以1
                $('#buy_number').val(buy_number);//把新值放回文本框
            }
        })
        $(document).on('click','#less',function(){
            //获取文本框的值
            var buy_number=parseInt($('#buy_number').val());
            if(buy_number<=1){
                //如果文本框中的值小于等于1
                $('#buy_number').val(1);//需要将文本框中的值改为1
            }else{
                buy_number-=1;//否则需要将文本框值每次减1
                $('#buy_number').val(buy_number);//把新值放回文本框
            }
        })
        //失去焦点
        $(document).on('blur','#buy_number',function(){
            var buy_number=$("#buy_number").val();//获取文本框中的值
            var goods_num=parseInt($(this).parents('tr').attr('goods_num'));//获取库存值
            var reg=/^\d{1,}$/; //验证是否是数字或者文本框中的值小于等于1
            if(!reg.test(buy_number)||parseInt(buy_number)<=1){
                $('#buy_number').val(1); //将文本框中的值改为1
            }else if(parseInt(buy_number)>=goods_num){
                //如果文本框的值大于等于库存值
                $('#buy_number').val(goods_num);//将文本框值改为库存值
            }else{
                $('#buy_number').val(parseInt(buy_number));//在前面都成立的情况下改为文本框中的值
            }
        })
        //加入购物车
        $(document).on('click','#addCart',function(){
            var buy_number=$("#buy_number").val();
            var goods_id="{{$goodsInfo->goods_id}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('cart/addCart')}}",
                {buy_number:buy_number,goods_id:goods_id},
                function(res){
                    if(res.code==1){
                        location.href="{{url('carlist')}}";
                    }else{
                        alert(res.font);
                        location.href="{{url('/login')}}";
                    }
                },
                'json'
            )
        })
    })

</script>
