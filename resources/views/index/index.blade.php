@extends('layouts.shop')
@section('title', '首页')
@section('content')
    <div class="head-top">
        <img src="{{ asset('/static/index/images/head.jpg') }}" />
        <dl>
            <dt><a href="user.html"><img src="{{ asset('/static/index/images/touxiang.jpg') }}" /></a></dt>
            <dd>
                <h1 class="username">三级分销终身荣誉会员</h1>
                <ul>
                    <li><a href="prolist.html"><strong>34</strong><p>全部商品</p></a></li>
                    <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
                    <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
                    <div class="clearfix"></div>
                </ul>
            </dd>
            <div class="clearfix"></div>
        </dl>
    </div><!--head-top/-->
    <form action="#" method="get" class="search">
        <input type="text" class="seaText fl" />
        <input type="submit" value="搜索" class="seaSub fr" />
    </form><!--search/-->
    <ul class="reg-login-click">
        <li><a href="{{url('/login')}}">登录</a></li>
        <li><a href="{{url('/reg')}}" class="rlbg">注册</a></li>
        <div class="clearfix"></div>
    </ul><!--reg-login-click/-->
    <div id="sliderA" class="slider">
        <img src="{{ asset('/static/index/images/image1.jpg') }}" />
        <img src="{{ asset('/static/index/images/image2.jpg') }}" />
        <img src="{{ asset('/static/index/images/image3.jpg') }}" />
        <img src="{{ asset('/static/index/images/image4.jpg') }}" />
        <img src="{{ asset('/static/index/images/image5.jpg') }}" />
    </div><!--sliderA/-->
    <ul class="pronav">
        @foreach($navInfo as $v)
        <li><a href="{{url('prolist/')}}?cate_id={{$v->cate_id}}">{{$v->cate_name}}</a></li>
       @endforeach
        <div class="clearfix"></div>
    </ul><!--pronav/-->
    <div class="index-pro1">
        @foreach($data as $v)
        <div class="index-pro1-list">
            <dl>
                <dt><a href="{{'proinfo'}}?goods_id={{$v->goods_id}}"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100px"/></a></dt>
                <dd class="ip-text"><a href="{{'proinfo'}}?goods_id={{$v->goods_id}}">{{$v->goods_name}}</a><span>已售：488</span></dd>
                <dd class="ip-price"><strong>¥{{$v->goods_price}}</strong> <span>¥599</span></dd>
            </dl>
        </div>
        @endforeach
        <div class="clearfix"></div>
    </div><!--index-pro1/-->
    <div class="prolist">
        <dl>
            <dt><a href="{{url('prolist/')}}"><img src="{{ asset('/static/index/images/prolist1.jpg') }}" width="100" height="100" /></a></dt>
            <dd>
                <h3><a href="proinfo.html">四叶草</a></h3>
                <div class="prolist-price"><strong>¥299</strong> <span>¥599</span></div>
                <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
            </dd>
            <div class="clearfix"></div>
        </dl>
    </div><!--prolist/-->
    <div class="joins"><a href="fenxiao.html"><img src="{{ asset('/static/index/images/jrwm.jpg') }}" /></a></div>
    <div class="copyright">Copyright &copy; <span class="blue">这是就是三级分销底部信息</span></div>
@endsection
