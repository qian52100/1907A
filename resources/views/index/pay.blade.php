@include('index.public.header')
@section('title', '确认结算')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="/static/index/images/head.jpg" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
          <tr>
              @if(empty($addressInfo))
              <td class="dingimg" width="75%" colspan="2"><a href="{{url('/address')}}">新增收货地址</a></td>
              @else
                      <tr address_id="{{$addressInfo->address_id}}">
                          <td class="p_td haha" >收货人姓名</td>
                          <td>{{$addressInfo->man}}</td>
                          <td class="p_td">电话</td>
                          <td >{{$addressInfo->phone}}</td>
                      </tr>
                  <tr>
                      <td colspan="4" style="height:10px; background:#efefef;padding:0;"></td>
                  </tr>
                       <tr>
                          <td class="p_td">详细信息</td>
                          <td>{{$addressInfo->detail_add}}</td>
                           <td class="p_td">省市区</td>
                           <td>{{$addressInfo->province}}{{$addressInfo->city}}{{$addressInfo->area}}</td>
                     </tr>
                  @endif
          <tr>
       </tr>
       <tr><td colspan="4" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2" >支付方式</td>
           <td style="position: relative;">
               <div style="width: 600px;position: absolute;top: 12px;left: -94px;" class="pay">
                   <b style="padding: 8px 14px;border: 1px solid black;"  pay_type="1">支付宝</b>
                   <b style="padding: 8px 14px;border: 1px solid black;" pay_type="2">微信</b>
                   <b style="padding: 8px 14px;border: 1px solid black;" pay_type="3">银行卡</b>
                   <b style="padding: 8px 14px;border: 1px solid black;" pay_type="4">找人代付</b>
               </div>
           </td>
       </tr>
       <tr><td colspan="4" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">优惠券</td>
        <td align="right"><span class="hui">无</span></td>
       </tr>
       <tr><td colspan="4" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
          @foreach($info as $v)
       <tr>
        <td class="dingimg" width="15%"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" /></td>
        <td width="50%">
         <h3>{{$v->goods_name}}</h3>
         <time>下单时间：{{date("Y-m-d H:i:s",$v->add_time)}}</time>
        </td>
        <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$v->buy_number*$v->goods_price}}</strong></th>
       </tr>
          @endforeach
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$count}}</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
        <td align="right"><strong class="green">¥0.00</strong></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">运费</td>
        <td align="right"><strong class="orange">¥20.80</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->


    </div><!--content/-->

    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$count}}</strong></td>
       <td width="40%"><a href="javascript:;" class="jiesuan addOrder">提交订单</a></td>
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
    $(document).on('click','.pay>b',function(){
        var _this=$(this);
        _this.addClass('red').siblings('b').removeClass('red');
    })
    $(document).on('click','.addOrder',function(){
        var goods_id="{{Request()->goods_id}}";
        var address_id=$('.haha').parent('tr').attr('address_id');
        var pay_type=$("b[class='red']").attr('pay_type');
        location.href="{{url('addOrder')}}?goods_id="+goods_id+'&address_id='+address_id+'&pay_type='+pay_type;
    })
</script>
