<?php 
    include('../includes/init.php');
    if (!$_POST) {
        exit("<script language='javascript'>alert('未知订单！');window.history.go(-1);</script>");
    }
    $val = getParam('domain')?getParam('domain'):getParam('ip');
    if (getParam('desc') == '解除域名拉黑') {
        $money = $conf['fee_cdo'];
    }elseif (getParam('desc') == '解除ＩＰ拉黑') {
        $money = $conf['fee_cip'];
    }
    $money = round($money,2);
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
                  <a><cite>订单结算</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md12">

            <div class="layui-card">
                <div class="layui-card-header">订单结算</div>
                <div class="layui-card-body">
                    <form class="layui-form layui-form-pane" action="">
                        <input type="hidden" name="check" value="0" class="layui-input">
                        <div class="layui-form-item">
                            <label class="layui-form-label">操作对象</label>
                            <div class="layui-input-block">
                                <input type="text" name="val" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=$val;?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">操作描述</label>
                            <div class="layui-input-block">
                                <input type="text" name="desc" required lay-verify="required" readonly="readonly" class="layui-input" value="<?=getParam('desc');?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">订单金额</label>
                            <div class="layui-input-block">
                                <span class="layui-form-label vip-goods-price text-pink">￥<?=$money;?></span>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="authadd">立即支付</button>
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
        if(!field.val)   return layer.msg('操作对象错误！');
        var loading = layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
        $.post('./ajax.php?method=Settlement', field, function(d, textStatus, xhr) {
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
                      <tr><td>操作对象：</td><td>' + d.domain + '</td></tr>\
                      <tr><td>操作描述：</td><td>'+d.desc+'</td></tr>\
                      <tr><td>扣费金额：</td><td><span class="vip-goods-price text-pink">￥'+d.money+'</span></td></tr>\
                      <tr><td colspan="2">支付扣款后，即使到账，即时生效。</td></tr>\
                      </tbody>\
                  </table>'
                }, function(){
                    field.check = 1;
                    $.post('./ajax.php?method=Settlement', field, function(j, textStatus, xhr) {
                        layer.alert(j.msg, function(){
                          window.history.go(-1);
                        });
                    },'json');
                    layer.close(pay);
                });
            }else{
                layer.msg(d.msg);
            }
        },'json');
        layer.close(loading);
        return false;
    });

});
</script>
</body>
</html>