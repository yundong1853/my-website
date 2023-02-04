<?php
    include('../includes/init.php');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title><?=$conf['name'];?> - 用户注册</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-carousel" id="vip-bg">
    <div carousel-item>
        <div><img src="../assets/user/img/background/01.jpg" alt=""></div>
        <div><img src="../assets/user/img/background/02.jpg" alt=""></div>
        <div><img src="../assets/user/img/background/03.jpg" alt=""></div>
    </div>
</div>

<canvas class="vip-reg-box-bg" id="lowPoly" width="500" height="400"></canvas>

<div class="vip-reg-box layui-form">
    <img src="../assets/user/img/web/logo.png" />
    <!--<h1>后台模板管理系统</h1>-->
    <div class="vip-reg-input-box">
        <div class="vip-input-box">
            <i class="vip-icon">&#xe6b3;</i>
            <input name="username" class="layui-input" type="text" placeholder="账户" lay-verify="required"/>
        </div>
        <div class="vip-input-box">
            <i class="vip-icon">&#xe669;</i>
            <input name="password" class="layui-input" type="password" placeholder="密码" lay-verify="required"/>
        </div>
        <div class="vip-input-box">
            <i class="vip-icon">&#xe669;</i>
            <input name="repass" class="layui-input" type="password" placeholder="确认密码" lay-verify="required"/>
        </div>
        <div class="vip-input-box">
            <i class="vip-icon2">&#xe7a0;</i>
            <input name="qq" class="layui-input" type="tel" maxlength="11" placeholder="绑定QQ" lay-verify="number"/>
        </div>
        <div class="vip-input-box vip-proving-box">
            <i class="vip-icon">&#xe66b;</i>
            <input name="pin" class="layui-input" type="text" maxlength="4" placeholder="验证码" lay-verify="required"/>
            <img src="./ajax.php?method=verifycode" id="verifycode" alt="验证码" onclick="this.src=this.src+'&'+Math.random();"/>
        </div>
        <div class="vip-input-box text-left">
            <input type="checkbox" name="agreement" lay-skin="primary" title="同意用户协议" checked="">
            <a target="_blank" style="float: right;color: #fff;" href="./login.php">用已有账号登录</a>
        </div>

    </div>
    <button type="button" class="layui-btn layui-bg-blue vip-reg-submit" lay-submit lay-filter="reg">提交注册</button>
</div>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
    layui.config({base: '../assets/user/js/'}).use(['carousel','vipLowPoly','form','layer'],function(){

        var $ = layui.$
        , carousel = layui.carousel
        , vipLowPoly = layui.vipLowPoly
        , form = layui.form
        , layer = layui.layer
        , flow = layui.flow;

        // 建造实例
        carousel.render({
            elem: '#vip-bg'
            ,full: true         // 是否全屏
            ,arrow: 'none'      // 始终不显示箭头
            ,autoplay: true     // 自动切换
            ,interval: '10000'  // 自动切换时间(ms)
            ,anim: 'fade'       // 切换动画方式
        });

        // 二维渲染(渲染对象，{开始颜色，结束颜色，运动速度})
        vipLowPoly.start('lowPoly',{startColor:'807dff',endColor:'222161',speed:25});

        // 记住密钥
        form.on('checkbox(remember-password)',function(data){
            // 判断是否记住密钥
            if(data.elem.checked){
                $('input[name=remember]').val(1);
            }
        });

        // 登入
        form.on('submit(reg)', function(data){
          var field = data.field;
          
          if(!field.username)                   return layer.msg('账户不能为空');
          if(!field.password)                   return layer.msg('密码不能为空');
          if(field.password !== field.repass)   return layer.msg('两次密码输入不一致');
          if(!field.qq)                         return layer.msg('绑定QQ不能为空');
          if(!field.pin)                    return layer.msg('验证码不能为空');
          if(!field.agreement)                  return layer.msg('你必须同意用户协议才能注册');
          layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
          $.ajax({
              url: 'ajax.php?method=register',
              type: 'post',
              dataType: 'json',
              data: field,
              success:function(msg){
                var strJson = JSON.stringify(msg) 
                var data = $.parseJSON(strJson);
                if (data.res == 1) {
                    layer.msg('注册成功', {
                      offset: '15px'
                      ,icon: 1
                      ,time: 1000
                    }, function(){
                      window.location.href = '/user/'; 
                    });
                }else{
                    $('#verifycode').attr('src','ajax.php?method=verifycode&'+Math.random());
                    layer.msg(data.msg);
                }
                console.log(data)
              },
              error:function(error){
                layer.alert("服务器超时！！");
              }
          });

          return false;
        });
    });
</script>

</body>
</html>