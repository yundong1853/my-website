<?php
    include('../includes/init.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$conf['name'];?> - 密码找回</title>
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

<canvas class="vip-login-box-bg" id="lowPoly" width="500" style="height: 400px;"></canvas>

<div class="vip-login-box layui-form" style="height: 400px;">
    <img src="../assets/user/img/web/logo.png" />
    <!--<h1>后台模板管理系统</h1>-->
    <div class="vip-login-input-box">
        <div class="vip-input-box">
            <i class="vip-icon">&#xe6b3;</i>
            <input id="username" class="layui-input" type="text" placeholder="账户" />
        </div>
         <div class="vip-input-box">
            <blockquote class="layui-elem-quote">
                <div class="text-center" id="login">
                    <span id="loginmsg">请先使用QQ手机版扫描二维码</span>
                    <span id="loginload">.</span>
                    <div class="page" id="qrimg"></div>
                    <button type="button" id="mobile" class="layui-btn layui-btn-sm layui-bg-green" style="display: none;" data-type="mlogin"><i class="vip-icon2">&#xe7a0;</i> 快捷登录</button>
                </div>
            </blockquote>
        </div>
    </div>
    <a class="layui-btn layui-bg-blue vip-login-submit" href="./login.php">返回登录</a>
</div>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript" src="../assets/plugin/qrcode/llqrcode.js"></script>
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

        $('body').on('click', '.layui-btn,img', function(){
          var othis = $(this), type = othis.data('type');
          active[type] && active[type].call(this, othis);
        });
        var active = {
            mlogin: function(div){
                qrcode.decode($('#qrimg img').attr('src'));
                qrcode.callback = function(imgMsg){
                    $.getJSON('https://api.uomg.com/api/long2dwz'
                        , {dwzapi: 'tcn',url:'https://api.uomg.com/api/qrcode?url='+encodeURIComponent(imgMsg)}
                        , function(json) {
                              var Ub = btoa('http://open.qzone.qq.com/url_check?url='+json.ae_url);
                              console.log(Ub);
                              window.location.href='mqqapi://forward/url?version=1&src_type=web&url_prefix='+Ub;
                    });
                }
            }
            ,qrpic:function(div){
                getqrpic();
            }
        }
        var interval1,interval2;
        function getqrpic(){
            var getvcurl='qrlogin.php?do=getqrpic&r='+Math.random(1);
            $.get(getvcurl, function(d) {
                if(d.saveOK ==0){
                    $('#qrimg').attr('qrsig',d.qrsig);
                    $('#qrimg').html('<img data-type="qrpic" src="data:image/png;base64,'+d.data+'" title="点击刷新">');
                    if( /Android|SymbianOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Windows Phone|Midp/i.test(navigator.userAgent) && navigator.userAgent.indexOf("QQ/") == -1) {
                        $('#mobile').show();
                    }
                }else{
                    alert(d.msg);
                }
            }, 'json');
        }
        function loadScript(c) {
            if ($('#login').attr("data-lock") === "true") return;
            var qrsig=$('#qrimg').attr('qrsig');
            $.getJSON('qrlogin.php?do=qqlogin', {qrsig: qrsig,r:Math.random(1)}, function(json, textStatus) {
                if(json.code==0) {
                    $.get("ajax.php?method=findpass", {username:$('#username').val(),r:Math.random(1)},function(arr) {
                        if(arr.res==1) {
                            cleartime();
                            $('#loginmsg').html('QQ已成功登录，正在保存...');
                            $('#login').hide();
                            $('#qrimg').hide();
                            $('#login').attr("data-lock", "true");
                            layer.alert(arr.msg, {
                              skin: 'layui-layer-molv'
                              ,closeBtn: 0
                            }, function(){
                              window.location.href='./login.php';
                            });
                        }else{
                            layer.msg(arr.msg);
                            getqrpic();
                        }
                    }, 'json');
                }else if(json.code==1){
                    getqrpic();
                }else if(json.code==3 || json.code==6){
                    $('#loginmsg').html(json.msg);
                }
            });
        }
        function loginload(){
            if ($('#login').attr("data-lock") === "true") return;
            var load=document.getElementById('loginload').innerHTML;
            var len=load.length;
            if(len>2){
                load='.';
            }else{
                load+='.';
            }
            document.getElementById('loginload').innerHTML=load;
        }
        function cleartime(){
            clearInterval(interval1);
            clearInterval(interval2);
        }
        $(document).ready(function(){
            getqrpic();
            interval1=setInterval(loginload,1000);
            interval2=setInterval(loadScript,3000);
        });
    });
</script>
</body>
</html>