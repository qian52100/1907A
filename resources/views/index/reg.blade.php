@extends('layouts.shop')
@section('title', '注册')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>会员注册</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="/static/index/images/head.jpg" />
    </div><!--head-top/-->
    <form action="" method="post" class="reg-login" id="myform">
        @csrf
        <h3>已经有账号了？点此<a class="orange" href="{{url('/login')}}">登陆</a></h3>
        <div class="lrBox">
            <div class="lrList"><input type="text"placeholder="输入手机号码或者邮箱号" name="user_tel" id="account"/></div>
            <div class="lrList2"><input type="text" placeholder="输入短信验证码" name="user_code" id="code"/> <button type="button" id="sendCode"><span id="span_tel">获取验证码</span></button></div>
            <div class="lrList"><input type="password" placeholder="设置新密码（6-18位数字或字母）" name="user_pwd" /></div>
            <div class="lrList"><input type="password" placeholder="再次输入密码" name="user_pwd1"/></div>
        </div><!--lrBox/-->
        <div class="lrSub">
            <input type="button" id="reg" value="立即注册" />
        </div>
    </form><!--reg-login/-->
    <div class="height1"></div>

</div><!--maincont-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="/static/index/js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="/static/index/js/bootstrap.min.js"></script>
<script src="/static/index/js/style.js"></script>
</body>
</html>
@endsection
<script src="/static/jquery.js"></script>
<script>
    $(document).ready(function(){
        $(document).on('click','#sendCode',function(){
            var account=$('#account').val();
            $('#span_tel').text('60s');
            _a=setInterval(gotimes,1000);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post(
                "{{url('login/sendCode')}}",
                {account:account},
                function(res){
                        alert(res.font);
                },
                'json'
            );
        })
        function gotimes(){
            var second=$('#span_tel').text();//获取当前纯文本 60s字符串
            second=parseInt(second);//转换数值类型 60
            if(second>0){
                second=second-1;
                $('#span_tel').text(second+'s');//拼接s
                $('#span_tel').css("pointer-events","none");
            }else{
                clearInterval(_a);
                $('#span_tel').text('获取');
                $('#span_tel').css("pointer-events","auto");
            }
        }
    })
    $(document).on('click','#reg',function(){
        var data=$("#myform").serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post(
            "{{url('login/Doreg')}}",
            data,
            function(res){
               if(res.code==1){
                   alert(res.font);
                   location.href="{{url('/login')}}";
               }else{
                   alert(res.font);
               }
            },
            'json'
        )
    })
</script>

