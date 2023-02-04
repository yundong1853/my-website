<?php 
    include('../includes/init.php');
    $row = $DB->get_row("select * from `uomg_auth` where id='".getParam('id')."' and uid='".$udata['id']."';");
    if (!$row) {
        exit("<script language='javascript'>alert('状态异常！');window.history.go(-1);</script>");
    }
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>续费授权</title>
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
                  <a onclick="parent.appHrefApi('授权管理','auth.php');">授权管理</a>
                  <a><cite>续费授权</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md12">

            <div class="layui-card">
                <div class="layui-card-header">续费授权</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <input type="hidden" name="id" value="<?=getParam('id');?>" class="layui-input">
                        <input type="hidden" name="check" value="0" class="layui-input">
                        <div class="layui-form-item">
                            <label class="layui-form-label">授权域名</label>
                            <div class="layui-input-block">
                                <input type="text" name="domain" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$row['domain'];?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">到期时间</label>
                            <div class="layui-input-block">
                                <input type="text" name="end_time" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$row['end_time'];?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">续费时间</label>
                            <div class="layui-input-block">
                                <input type="radio" name="time" value="7" title="7天">
                                <input type="radio" name="time" value="30" title="30天" checked>
                                <input type="radio" name="time" value="90" title="90天">
                                <input type="radio" name="time" value="180" title="180天">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="authadd">立即提交</button>
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


    form.on('select', function(data){
        var imgurl = $(data.elem).find('option:selected').attr('img');
        $('#'+data.elem.title).attr('src',imgurl);
        console.log(data);
    });
    // 充值
    form.on('submit(authadd)', function(data){
        console.log(JSON.stringify(data.field));
        var field = data.field;
        if(!field.domain)   return layer.msg('授权域名错误！');
        var loading = layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
        $.post('./ajax.php?method=authren', field, function(d, textStatus, xhr) {
            if (d.res == 1) {
                var pay = layer.confirm('订单信息', {
                    type: 1,
                    title: '订单信息',
                    skin: 'layui-layer-demo', 
                    closeBtn: 0,
                    anim: 2,
                    width: 300,
                    shadeClose: true,
                    btn: ['立即支付','取消支付'], 
                    content: '<table class="layui-table" lay-size="md" lay-even="" lay-skin="row" style="padding:20px;">\
                      <tbody>\
                      <tr><td>续费域名：</td><td>' + d.domain + '</td></tr>\
                      <tr><td>续费类目：</td><td>'+d.type+'</td></tr>\
                      <tr><td>到期时间：</td><td>'+d.time+'</td></tr>\
                      <tr><td>扣费金额：</td><td><span class="vip-goods-price text-pink">￥'+d.money+'</span></td></tr>\
                      <tr><td colspan="2">支付扣款后，即使到账，即时生效。</td></tr>\
                      </tbody>\
                  </table>'
                }, function(){
                    field.check = 1;
                    $.post('./ajax.php?method=authren', field, function(j, textStatus, xhr) {
                        layer.alert(j.msg, function(){
                          window.history.go(-1);
                        });
                    },'json');
                    layer.close(pay);
                });
            }else{
                layer.msg(d.msg);
            }
            layer.close(loading);
        },'json');
        return false;
    });

});
</script>
</body>
</html>