<?php
    include('../includes/init.php');

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>价目表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote mar-no">
                <span class="layui-breadcrumb">
                  <a onclick="parent.appHrefApi('仪表板','dashboard.php');">首页</a>
                  <a><cite>价目表</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md3 layui-col-sm6">
            <div class="vip-version-panel vip-box-shadow text-center">
                <h2>泛域名授权</h2>
                <span class="layui-badge-rim">专属人工客服</span>
                <ul>
                    <li><?=$conf['fee_f7']?>元7天</li>
                    <li><?=$conf['fee_f30']?>元30天</li>
                    <li><?=$conf['fee_f90']?>元90天</li>
                    <li><?=$conf['fee_f180']?>元180天</li>
                </ul>
                <a href="./auth-add.php" class="layui-btn layui-btn-sm layui-btn-fluid">选择</a>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6">
            <div class="vip-version-panel vip-box-shadow text-center">
                <h2>单域名授权</h2>
                <span class="layui-badge-rim">专属人工客服</span>
                <ul>
                    <li><?=$conf['fee_d7']?>元7天</li>
                    <li><?=$conf['fee_d30']?>元30天</li>
                    <li><?=$conf['fee_d90']?>元90天</li>
                    <li><?=$conf['fee_d180']?>元180天</li>
                </ul>
                <a href="./auth-add.php" class="layui-btn layui-btn-sm layui-btn-fluid">选择</a>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6">
            <div class="vip-version-panel vip-box-shadow text-center">
                <h2>解除域名拉黑</h2>
                <span class="layui-badge-rim">专属人工客服</span>
                <ul>
                    <li><?=$conf['fee_cdo']?>元/次</li>
                    <li>解除域名拉黑</li>
                    <li>解除拉黑不同于授权</li>
                    <li>如有疑问请联系客服</li>
                </ul>
                <a href="./relieve-domain.php" class="layui-btn layui-btn-sm layui-btn-fluid">选择</a>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6">
            <div class="vip-version-panel vip-box-shadow text-center">
                <h2>解除ＩＰ拉黑</h2>
                <span class="layui-badge-rim">专属人工客服</span>
                <ul>
                    <li><?=$conf['fee_cip']?>元/次</li>
                    <li>解除ip黑名单</li>
                    <li>解除拉黑不同于授权</li>
                    <li>如有疑问请联系客服</li>
                </ul>
                <a href="./relieve-ip.php" class="layui-btn layui-btn-sm layui-btn-fluid">选择</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['layer','app','element'],function(){
    var $ = layui.$
    , layer = layui.layer
    , app = layui.app
    , element = layui.element;

});
</script>
</body>
</html>