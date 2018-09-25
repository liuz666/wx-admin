<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台-登录</title>
    <link href="//cdn.bootcss.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        img{
            width:100%;
            height:100%;
            position:absolute;
            z-index:-1;
            top:0;
        }
        section{
            width:400px;
            height:300px;
            margin-top:15%;
            text-align:center;
            margin:10% auto;
            border-radius:5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li ><a href="#" ><span> 后台-管理</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <img src="/Public/bg2.jpg" alt="">
    <section>
        
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">
                    后台登录
                </h3>
            </div>
            <div class="panel-body">
                <form action="" class="form-horizontal loginForm">
                    <div class="form-group">
                        <label for="firstname" class="col-sm-2 control-label">用户</label>
                        <div class="col-sm-10">
                            <input type="text" id="account" class="form-control" name="account" placeholder="请输入用户名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">
                            <input type="password" id="ps" class="form-control" name="password" placeholder="请输入密码" onkeydown="keydown(event)">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" id="btnLogin" class="btn btn-success ">确认登录</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </section>
    <script src="/Public/js/jquery.min.js"></script>
    <script>
        function keydown(e){
            var e = e || event;
            if (e.keyCode==13){
                $("#btnLogin").click();
            }
        }
        $('#btnLogin').click(function(){    
            var account = $('#account').val();
            var ps = $('#ps').val();
            if(ps == '' || account == ''){
                alert("账号密码不能为空");
                return;
            }
            $.post('/index.php/Home/Login/usLogin',{'account':account,'ps':ps},function(data){
                if(data.code == 0){
                    alert(data.info)
                }else{
                    alert(data.info);
                    window.location.href="/test-vue/#/index.html";
                }
            }) 
         })
         // function isPhone(str){
         //    var reg = /^1([3|5|8]{1})\d{9}$/;
         //    if(reg.test(str)){
         //        return true;
         //    }else{
         //        return false;
         //    }
         // }
    </script>
   
</body>
</html>