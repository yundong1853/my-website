<?php 
    include('../includes/init.php');
    $tpls=showStasisPagelist();
    $tplnums = $tpls['nums'];
    unset($tpls['nums']);
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>添加授权</title>
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
                  <a><cite>添加授权</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md12">

            <div class="layui-card">
                <div class="layui-card-header">添加授权</div>
                <div class="layui-card-body">
                    <form class="layui-form" action="">
                        <input type="hidden" name="check" value="0" class="layui-input">
                        <div class="layui-form-item">
                            <label class="layui-form-label">授权域名</label>
                            <div class="layui-input-block">
                                <input type="text" name="domain" lay-verify="required" autocomplete="off" placeholder="泛域名例如：*.domain.com" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">苹果端模板</label>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="iosqq" title="iosqq" ae_val="<?=$conf['stasis_iosqq'];?>">
                                        <option value="">请选择ＱＱ跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="iosvx" title="iosvx" ae_val="<?=$conf['stasis_ioswx'];?>">
                                        <option value="">请选择ＶＸ跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">苹果端预览</label>
                            <div class="layui-col-xs3">
                                <img id="iosqq" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                            <div class="layui-col-xs3">
                                <img id="iosvx" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">安卓端模板</label>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="anqq" title="anqq" ae_val="<?=$conf['stasis_anqq'];?>">
                                        <option value="">请选择ＱＱ跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="anvx" title="anvx" ae_val="<?=$conf['stasis_anwx'];?>">
                                        <option value="">请选择ＶＸ跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">安卓端预览</label>
                            <div class="layui-col-xs3">
                                <img id="anqq" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                            <div class="layui-col-xs3">
                                <img id="anvx" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">其他端模板</label>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="other" title="other" ae_val="<?=$conf['stasis_other'];?>">
                                        <option value="">其他浏览器跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="alipay" title="alipay" ae_val="<?=$conf['stasis_alipay'];?>">
                                        <option value="">支付宝跳转风格</option>
                                        <?php foreach($tpls as $key=>$value){
                                            echo '<option value="'.$value['file'].'" img="'.$value['img'].'">'.$value['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">其他端预览</label>
                            <div class="layui-col-xs3">
                                <img id="other" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                            <div class="layui-col-xs3">
                                <img id="alipay" src="https://ae01.alicdn.com/kf/HTB1qsaJThTpK1RjSZFK7612wXXaW.png" class="uomg-temp-img">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">授权时间</label>
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

    $('select').each(function(i,le){
      var t = $(this).attr('ae_val');
      $(this).val(t);
      var imgurl = $(this).find('option:selected').attr('img');
      $('#'+this.title).attr('src',imgurl);
    });
    form.render('select');
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
        $.post('./ajax.php?method=authadd', field, function(d, textStatus, xhr) {
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
                      <tr><td>授权域名：</td><td>' + d.domain + '</td></tr>\
                      <tr><td>授权类型：</td><td>'+d.type+'</td></tr>\
                      <tr><td>授权时间：</td><td>'+d.time+'天</td></tr>\
                      <tr><td>扣费金额：</td><td><span class="vip-goods-price text-pink">￥'+d.money+'</span></td></tr>\
                      <tr><td colspan="2">支付扣款后，即使到账，即时生效。</td></tr>\
                      </tbody>\
                  </table>'
                }, function(){
                    var loading = layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
                    field.check = 1;
                    $.post('./ajax.php?method=authadd', field, function(j, textStatus, xhr) {
                        layer.close(loading);
                        layer.alert(j.msg, function(){
                          window.location.href='./auth.php';
                        });
                    },'json');
                    layer.close(pay);
                });
            }else{
                layer.msg(d.msg);
            }
        },'json');
        return false;
    });

    $('input[name="domain"]').on("change", function () {
        let url = $(this).val();
        if (url.indexOf('/') > -1) {
            var myURL = parseURL(url);
            if (myURL.host == window.location.host) {
                layer.msg('请输入域名，不要带http://和/！');
            }else{
                $(this).val(myURL.host);
            }
        }
    });
    
    function parseURL(url) { 
        var a = document.createElement('a'); 
        a.href = url; 
        return { 
            source: url, 
            protocol: a.protocol.replace(':',''), 
            host: a.hostname, 
            port: a.port, 
            query: a.search, 
            params: (function(){ 
                var ret = {}, 
                seg = a.search.replace(/^\?/,'').split('&'), 
                len = seg.length, i = 0, s; 
                for (;i<len;i++) { 
                    if (!seg[i]) { continue; } 
                    s = seg[i].split('='); 
                    ret[s[0]] = s[1]; 
                } 
                return ret; 
            })(), 
            file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1], 
            hash: a.hash.replace('#',''), 
            path: a.pathname.replace(/^([^\/])/,'/$1'), 
            relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1], 
            segments: a.pathname.replace(/^\//,'').split('/') 
        }; 
    }

});
</script>
</body>
</html>