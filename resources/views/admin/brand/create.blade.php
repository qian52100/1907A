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
<h3 align="center">品牌添加</h3><a href="{{url('brand')}}">列表展示</a><hr>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<form class="form-horizontal" role="form" action="{{url('brand/store')}}" method="post" enctype="multipart/form-data">
    @csrf;
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">品牌名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="brand_name" id="brand_name"
                   placeholder="请输入品牌名称">
            <b style="color:red;">{{$errors->first('brand_name')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌网址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="brand_url" id="brand_url"
                   placeholder="请输入网址">
            <b style="color:red;">{{$errors->first('brand_url')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌LOGO</label>
        <div class="col-sm-10">
            <input type="file" class="form-control" name="brand_logo">
        </div>
    </div>
    <div class="form-group">
        <label for="lastname" class="col-sm-2 control-label">品牌描述</label>
        <div class="col-sm-10">
            <textarea type="text" class="form-control" name="brand_desc" id="brand_desc"
                      placeholder="请输入品牌描述"></textarea>
            <b style="color:red;">{{$errors->first('brand_desc')}}</b>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">添加</button>
        </div>
    </div>
    <script>
        $("#brand_name").blur(function(){
            checkName();
        })

        $('input[name="brand_url"]').blur(function(){
           checkUrl();
        })
        $(".btn-default").click(function(){
            var res1=checkName();
            var res2=checkUrl();
            if(res1&&res2){
                $(".form-horizontal").submit();
            }
            return false;
        })
        function checkName(){
            $("#brand_name").next().text('');
            var brand_name= $("#brand_name").val();
            var reg=/^[\u4e00-\u9fa5\w]{2,12}$/;
            if(!reg.test(brand_name)){
                $("#brand_name").next().text('品牌名称允许中文数字字母下划线组成2-12位');
                return false;
            }
            return checkOnly(brand_name);
        }
        function checkUrl(){
            $('input[name="brand_url"]').next().text('');
            var brand_url=$('input[name="brand_url"]').val();
            var reg=/^http:\/\/*/;
            if(!reg.test(brand_url)){
                $('input[name="brand_url"]').next().text('网址需以http://开头');
                return false;
            }
            return true;
        }
        function checkOnly(brand_name){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var flag=true;
            $.ajax({
                method:"post",
                url:"{{url('brand/checkName')}}",
                async:false,
                data:{brand_name:brand_name}
            }).done(function(res){
                if(res=='no'){
                    $("#brand_name").next().text('品牌名称已存在');
                    flag=false;
                }
            })
            return flag;
        }
    </script>
</form>
</body>
</html>
<script src="/static/jquery.js"></script>
{{--<script>--}}
{{--    $(document).ready(function(){--}}
{{--        $(document).on('blur','#myform',function(){--}}
{{--            var res1=checkName();--}}
{{--            if(res1==false){--}}
{{--                return false;--}}
{{--            }--}}
{{--            var res2=checkUrl();--}}
{{--            if(res2==false){--}}
{{--                return false;--}}
{{--            }--}}
{{--            var res3=checkDesc();--}}
{{--            if(res3==false){--}}
{{--                return false;--}}
{{--            }--}}

{{--            return true;--}}
{{--        })--}}
{{--        //验证品牌名称--}}
{{--        function checkName(){--}}
{{--            var brand_name=$("#brand_name").val();--}}
{{--            var reg_name=/^[\u4e00-\u9fa5]{2,4}$/;--}}
{{--            if(brand_name==''){--}}
{{--                alert('品牌名称必填');--}}
{{--                return false;--}}
{{--            }else if(!reg_name.test(brand_name)){--}}
{{--                alert('品牌名称允许汉字 组成2-4位');--}}
{{--                return false;--}}
{{--            }else{--}}
{{--                var flag=false;--}}
{{--                $.ajaxSetup({--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                    }--}}
{{--                });--}}
{{--                $.ajax({--}}
{{--                    method:"post",--}}
{{--                    url:"{{url('brand/checkName')}}",--}}
{{--                    data:{brand_name:brand_name},--}}
{{--                    async:false--}}
{{--                }).done(function(res){--}}
{{--                    if(res=='no'){--}}
{{--                        alert('品牌名称已存在');--}}
{{--                        flag=false;--}}
{{--                    }else{--}}
{{--                        flag=true;--}}
{{--                    }--}}
{{--                })--}}
{{--                return flag;--}}
{{--            }--}}

{{--        }--}}
{{--        //验证品牌网址--}}
{{--        function checkUrl(){--}}
{{--            var brand_url=$("#brand_url").val();--}}
{{--            var reg_url=/^http:///;--}}
{{--            if(brand_url==''){--}}
{{--                alert('品牌网址必填');--}}
{{--                return false;--}}
{{--            }else if(!reg_url.test(brand_url)){--}}
{{--                alert('品牌网址格式有误');--}}
{{--                return false;--}}
{{--            }else{--}}
{{--                return true;--}}
{{--            }--}}
{{--        }--}}
{{--        //验证品牌描述--}}
{{--        function checkDesc(){--}}
{{--            var brand_desc=$("#brand_desc").val();--}}
{{--            if(brand_desc==''){--}}
{{--                alert('品牌描述必填');--}}
{{--                return false;--}}
{{--            }else{--}}
{{--                return true;--}}
{{--            }--}}
{{--        }--}}
{{--    })--}}
{{--</script>--}}
