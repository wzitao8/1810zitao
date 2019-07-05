<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="mui-input-row">
        <label>用户名</label>
        <input id='username' type="text" placeholder="请输入用户名">
    </div>
    <div class="mui-input-row">
        <label>密码</label>
        <input id='password' type="password"  placeholder="请输入密码">
    </div>
    {{--<input type="hidden" id='referer' name="referer" value="{{$referer}}">--}}
    <input id="sub" type="submit" value="登陆">
</body>
</html>
<script src="/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#sub').click(function () {
            // alert('123');
            var username = $('#username').val();
            var pwd = $('#password').val();
            // console.log(username);
            if(username == ''){
                return alert('用户名必填');
            }else if (pwd ==''){
                return alert('密码必填');
            }else{
                $.post(
                    "http://pass.1810shop.com/logindo",
                    {username:username,pwd:pwd},
                    function(res){
                        console.log(res);
                        // if (res =='2') {
                        //     return layer.msg('请输入正确邮箱密码', {icon: 2});
                        //     // alert('2');
                        // }else{
                        //     layer.msg('登陆成功', {icon: 1});
                        //     location.href=res;
                        // }

                    }
                );
            }


        })
    })
</script>
