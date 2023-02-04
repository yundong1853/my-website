<?php 
    include('../includes/init.php');
    $row = $DB->get_row("select * from `uomg_report` where id='".getParam('id')."' and aid='".$udata['id']."';");
    if (!$row) {
        exit("<script language='javascript'>alert('状态异常！');window.history.go(-1);</script>");
    }
    $tpls=showStasisPagelist();
    $tplnums = $tpls['nums'];
    unset($tpls['nums']);
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>修改记录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
    <style type="text/css">
        .uomg-temp-img{width:50%;}
    </style>
<body ontouchstart="">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote mar-no">
                <span class="layui-breadcrumb">
                  <a onclick="parent.appHrefApi('仪表板','dashboard.php');">首页</a>
                  <a onclick="parent.appHrefApi('授权管理','auth.php');">记录管理</a>
                  <a><cite>修改记录</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md12">

            <div class="layui-card">
                <div class="layui-card-header">修改授权</div>
                <div class="layui-card-body">
                    <form class="layui-form" action="">
                        <input type="hidden" name="id" value="<?=getParam('id');?>" class="layui-input">
                        <input type="hidden" name="check" value="0" class="layui-input">
                        <div class="layui-form-item">
                            <label class="layui-form-label">源站网址</label>
                            <div class="layui-input-block">
                                <input type="text" name="url" required lay-verify="required" autocomplete="off" placeholder="http://www.baidu.com/" class="layui-input" value="<?=$row['url'];?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">网站标题</label>
                            <div class="layui-input-block">
                                <input type="text" name="title" class="layui-input" value="<?=$row['title'];?>">
                            </div>
                        </div>
                        <div class="layui-form-item layui-hide">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit id="report-edit-submit" lay-filter="report-edit-submit">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>



<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['layer','table','form','app','element'],function(){
    var $ = layui.$
    , layer = layui.layer
    , table = layui.table
    , form = layui.form
    , app = layui.app
    , element = layui.element;

});
</script>
</body>
</html>