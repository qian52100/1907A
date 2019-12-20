@include('index.public.header')
@section('title', '购物车')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>购物车</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="/static/index/images/head.jpg" />
    </div><!--head-top/-->
    <table class="shoucangtab">
        <tr>
            <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
            <td width="25%" align="center" style="background:#fff url(/static/index/images/xian.jpg) left center no-repeat;">
                <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
            </td>
        </tr>
    </table>

    <div class="dingdanlist">
        <table>
            <tr>
                <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" id="allBox"/> 全选</a></td>
            </tr>
            @foreach($cartInfo as $v)
            <tr goods_num="{{$v->goods_num}}" goods_id="{{$v->goods_id}}">
                <td width="4%"><input type="checkbox" name="1" class="box"/></td>
                <td class="dingimg" width="15%"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" /></td>
                <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <time>下单时间：{{date("Y-m-d H:i:s",$v->add_time)}}</time>
                    <span >单价: ￥{{$v->goods_price}}</span>
                    <strong class="orange total">小计:¥{{$v->goods_price*$v->buy_number}}</strong>
                </td>
                <td align="right">
                    <span class="less">-</span>
                    <span><input type="text" value="{{$v->buy_number}}" class='buy_number' style="width:50px; height=40px" ></span>
                    <span class="add">+</span>
                    <span><a href="javascript:;" class="del">删除</a></span>
                </td>
            </tr>
            @endforeach
            <tr width="100%" ><td colspan="4"><a href="javascript:;" id="delMany">删除选中商品</a></td></tr>
        </table>
    </div><!--dingdanlist/-->


    <div class="height1"></div>
    <div class="gwcpiao">
        <table>
            <tr>
                <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
                <td width="50%">总计：<strong class="orange" id="count">¥0</strong></td>
                <td width="40%"><a href="javascript:;" class="jiesuan" id="confirmOrder">去结算</a></td>
            </tr>
        </table>
    </div><!--gwcpiao/-->
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
<script src="/static/jquery.js"></script>
<script>
    $(function(){
        //点击+号
        $(document).on('click','.add',function(){
            var _this=$(this);
            var buy_number=parseInt(_this.prev('span').children('input').val());
            var goods_id=parseInt(_this.parents('tr').attr('goods_id'));
            var goods_num=parseInt(_this.parents('tr').attr('goods_num'));
            //文本框数量+1
            if(buy_number>=goods_num){
                _this.prev('span').children('input').val(goods_num);
            }else{
                buy_number+=1;
                _this.prev('span').children('input').val(buy_number);
            }
            //购买数量改为文本框的值
            changeNum(goods_id,buy_number,_this);
            //给当前行变色
            changeColor(_this);
            //给当前行上的复选框选中状态
            changeChecked(_this);
            //重新获取小计
            changeTotal(_this,goods_id);
            //重新获取总价
            changeCount();

        })
        //点击-号
        $(document).on('click','.less',function(){
            var _this=$(this);
            var buy_number=parseInt(_this.next('span').children('input').val());
            var goods_id=_this.parents('tr').attr('goods_id');
            //文本框数量-1
            if(buy_number<=1){
                _this.next('span').children('input').val(1);
            }else{
                buy_number-=1;
                _this.next('span').children('input').val(buy_number);
            }
            //购买数量改为文本框的值
            changeNum(goods_id,buy_number,_this);
            //当前行复选框选中
            changeChecked(_this);
            //给当前行变色
            changeColor(_this);
            //重新获取小计
            changeTotal(_this,goods_id);
            //重新获取总价
            changeCount();
        })
        //失去焦点
        $(document).on('blur','.buy_number',function(){
            var _this=$(this);
            var buy_number=_this.val();
            var goods_id=_this.parents('tr').attr('goods_id');
            var goods_num=parseInt(_this.parents('tr').attr('goods_num'));
            var reg=/^\d{1,}$/;
            if(!reg.test(buy_number)||parseInt(buy_number)<=1){
                _this.val(1);
                buy_number=1;
            }else if(parseInt(buy_number)>=goods_num){
                _this.val(goods_num);
                buy_number=goods_num;
            }else{
                _this.val(parseInt(buy_number));
            }
            //购买数量改为文本框值
            changeNum(goods_id,buy_number,_this);
            //当前行复选框选中
            changeChecked(_this);
            //当前行变色
            changeColor(_this);
            //重新获取小计
            changeTotal(_this,goods_id);
            //重新获取总价
            changeCount();
        })
        //点击复选框
        $(document).on('click','.box',function(){
            var _this=$(this);
            var status=_this.prop('checked');
            if(status==true){
               //当前行变色
                changeColor(_this);
            }else{
                _this.parents('tr').removeClass('red');
            }
            //重新获取总价
            changeCount();
        })
        //点击全选
        $(document).on('click','#allBox',function(){
            var _this=$(this);
            var status=_this.prop('checked');
            if(status==true){
                $("tr[goods_id]").addClass('red');
            }else{
                $("tr[goods_id]").removeClass('red');
            }
            $(".box").prop('checked',status);
            //重新获取总价
            changeCount();
        })
        //点击删除
        $(document).on('click','.del',function(){
            var _this=$(this);
            var goods_id=_this.parents('tr').attr('goods_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('cart/del')}}",
                {goods_id:goods_id},
                function(res){
                    if(res.code==1){
                       _this.parents('tr').remove();
                        //重新获取总价
                        changeCount();
                    }else{
                        alert(res.font);
                    }
                },
                'json'
            )
        })
        //删除选中商品
        $(document).on('click','#delMany',function(){
            var _box=$(".box:checked");
            if(_box.length>0){
                var goods_id="";
                _box.each(function(index){
                    goods_id+=$(this).parents('tr').attr('goods_id')+',';
                })
                goods_id=goods_id.substr(0,goods_id.length-1);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "{{url('cart/del')}}",
                    {goods_id:goods_id},
                    function(res){
                        if(res.code==1){
                         _box.parents('tr').remove();
                            $("#count").text('￥0 ');
                        }else{
                            alert(res.font);
                        }
                    },
                    'json'
                )
            }else{
                alert('您还没有选中商品');
                return false;
            }
        })
        //重新获取小计
        function changeTotal(_this,goods_id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('cart/changeTotal')}}",
                {goods_id:goods_id},
                function(res){
                  _this.parents('tr').find('.total').text('小计:'+'￥'+res);
                }
            )
        }
        //给当前行变色
        function changeColor(_this){
            _this.parents('tr').addClass('red');
        }
        //给当前行上的复选框选中状态
        function  changeChecked(_this){
            _this.parents('tr').find('.box').prop('checked',true);
        }
        //购买数量改为文本框的值
        function changeNum(goods_id,buy_number,_this){
            var flag=1;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method:"post",
                url:"{{url('cart/changeNum')}}",
                data:{goods_id:goods_id,buy_number:buy_number},
                async:false
            }).done(function(res){
                if(res=='no'){
                    alert('数量修改失败');
                    _this.prev('span').children('input').val(buy_number+1);
                    var flag=2;
                }
            })
            if(flag==2){
                return false;
            }
        }
        //重新获取总价
        function changeCount(){
            var _box=$(".box:checked");
            var goods_id="";
            _box.each(function(index){
                goods_id+=$(this).parents('tr').attr('goods_id')+',';
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post(
                "{{url('cart/changeCount')}}",
                {goods_id:goods_id},
                function(res){
                    $("#count").text("￥"+res);
                }
            )
        }
        //点击确认结算
        $(document).on('click','#confirmOrder',function(){
            var goods_id='';
            var _box=$('.box:checked');
            if(_box.length>0){
                _box.each(function(index){
                    goods_id+=$(this).parents('tr').attr('goods_id')+',';
                })
                goods_id=goods_id.substr(0,goods_id.length-1);
                location.href="{{url('order/pay')}}?goods_id="+goods_id;
            }else{
                alert('请至少选择一件商品');
            }
        })
    })

</script>
