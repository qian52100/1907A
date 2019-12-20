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
</head>
<body>
<h3 align="center">登陆</h3><hr>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">用户登录</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{url('user/userDo')}}" method="post">
                        @csrf
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="账号" name="account">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="密码" name="pwd" type="password">
                            </div>

                            <!-- Change this to a button or input when using this as a form -->

                            <input type="submit" value="登陆"  class="btn btn-lg btn-success btn-block">


                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
