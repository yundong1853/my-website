<?php
/**
 * 
**/
include("../includes/init.php");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>仪表板</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-fluid">

    <div class="layui-row layui-col-space15 vip-dashboard-count-2">
        <div class="layui-col-md3 layui-col-sm6 layui-col-sx12">
            <div class="vip-panel">
                <div class="vip-panel-title bg-info"></div>
                <div class="vip-panel-body" style="padding-bottom: 7px;">
                    <div class="vip-panel-body-img-box">
                        <img src="https://q2.qlogo.cn/headimg_dl?spec=100&dst_uin=<?=$udata['qq'];?>"/>
                        <p style="font-size: 17px;"><?=$udata['username'];?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6 layui-col-sx12">
            <div class="vip-chart bg-info">
                <div class="vip-chart-content">
                    <h5 class="layui-elip">账户余额</h5>
                    <h2 class="layui-elip"><?=number_format($udata['money'],2);?></h2>
                </div>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6 layui-col-sx12">
            <div class="vip-chart bg-success">
                <div class="vip-chart-content">
                    <h5 class="layui-elip">登录时间</h5>
                    <h2 class="layui-elip"><?=$udata['login_time'];?></h2>
                </div>
            </div>
        </div>
        <div class="layui-col-md3 layui-col-sm6 layui-col-sx12">
            <div class="vip-chart bg-mint">
                <div class="vip-chart-content">
                    <h5 class="layui-elip">登录ＩＰ</h5>
                    <h2 class="layui-elip"><?=$udata['login_ip'];?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space15">
        <div class="layui-col-md5">
            <div class="layui-card">
                <div class="layui-card-header">常用快捷入口</div>
                <div class="layui-card-body">
                    <ul class="layui-row layui-col-space10 vip-entry-box">
                       <li class="layui-col-xs3" vip-href="user.php" vip-title="修改资料" title="修改资料" >
                           <i class="vip-icon">&#xe6b5;</i>
                           <p class="layui-elip">修改资料</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="pass.php" vip-title="修改密码" title="修改密码">
                           <i class="vip-icon">&#xe663;</i>
                           <p class="layui-elip">修改密码</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="bill.php" vip-title="账户明细" title="账户明细">
                           <i class="vip-icon">&#xe676;</i>
                           <p class="layui-elip">账户明细</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="auth.php" vip-title="授权列表" title="授权列表">
                           <i class="vip-icon">&#xe660;</i>
                           <p class="layui-elip">授权列表</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="auth-add.php" vip-title="添加授权" title="添加授权">
                           <i class="vip-icon">&#xe709;</i>
                           <p class="layui-elip">添加授权</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="report.php" vip-title="添加授权" title="添加授权">
                           <i class="vip-icon">&#xe66c;</i>
                           <p class="layui-elip">生成记录</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="#" vip-vip-title="暂空" vip-title="暂空">
                           <i class="vip-icon">&#xe642;</i>
                           <p class="layui-elip">暂空</p>
                       </li>
                       <li class="layui-col-xs3" vip-href="#" vip-vip-title="暂空" vip-title="暂空">
                           <i class="vip-icon">&#xe767;</i>
                           <p class="layui-elip">暂空</p>
                       </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="layui-col-md7">
            <div class="layui-card">
                <div class="layui-card-header">公告</div>
                <div class="layui-card-body ">
                    <ul class="layui-row layui-col-space10 vip-notice-box">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space15 vip-dashboard-count">
    </div>
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">生成短链</div>
                <div class="layui-card-body layui-form">
                    <style>
                        .layui-form-item{margin-bottom: 5px;}
                        .layui-form-item img{width:14px;height:14px;margin:-3px 5px 0 3px;}
                    </style>
                    <div class="layui-form-item">
                      <div class="layui-col-xs12 layui-col-sm3">
                        <select name="type">
                          <option value="ty">通用链接</option>
                          <option value="vx">微信专用</option>
                          <option value="qq">ＱＱ专用</option>
                        </select>     
                      </div>
                      <div class="layui-col-xs12 layui-col-sm6">
                        <input type="text" name="longurl" required lay-verify="required" placeholder="请输入要生成的网址例如：http://www.baidu.com/" autocomplete="off" class="layui-input"/>
                      </div>
                      <div class="layui-col-xs12 layui-col-sm3">
                        <button type="button" name="dwzapi" class="layui-btn layui-btn-fluid layui-bg-gray" value="3" lay-submit lay-filter="create">点我生成</button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">充值余额</div>
                <div class="layui-card-body layui-form">
                    <style>
                        .layui-form-item{margin-bottom: 5px;}
                        .layui-form-item img{width:14px;height:14px;margin:-3px 5px 0 3px;}
                    </style>
                    <div class="layui-form-item">
                        <input type="hidden" name="name" value="在线充值余额" lay-verify="required">
                        <label class="layui-form-label">充值金额</label>
                        <div class="layui-input-block">
                            <input type="text" name="money" autocomplete="off" placeholder="要充值的金额" class="layui-input" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <?php if ($conf['alipay_api']) {?>
                        <div class="layui-col-md3 layui-col-sm6 layui-col-sx6">
                            <button type="button" name="do" class="layui-btn layui-btn-fluid layui-bg-gray" value="alipay" lay-submit lay-filter="recharge"><img src="../assets/icon/alipay.ico">支付宝</button>
                        </div>
                        <?php }if ($conf['qqpay_api']) {?>
                        <div class="layui-col-md3 layui-col-sm6 layui-col-sx6">
                            <button type="button" name="do" class="layui-btn layui-btn-fluid layui-bg-gray" value="qqpay" lay-submit lay-filter="recharge"><img src="../assets/icon/qqpay.ico">QQ钱包</button>
                        </div>
                        <?php }if ($conf['wxpay_api']) {?>
                        <div class="layui-col-md3 layui-col-sm6 layui-col-sx6">
                            <button type="button" name="do" class="layui-btn layui-btn-fluid layui-bg-gray" value="wxpay" lay-submit lay-filter="recharge"><img src="../assets/icon/wechat.ico">微信支付</button>
                        </div>
                        <?php }if ($conf['tenpay_api']) {?>
                        <div class="layui-col-md3 layui-col-sm6 layui-col-sx6">
                            <button type="button" name="do" class="layui-btn layui-btn-fluid layui-bg-gray" value="tenpay" lay-submit lay-filter="recharge"><img src="../assets/icon/tenpay.ico">财付通</button>
                        </div>    
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">常见问题</div>
                <div class="layui-card-body">
                    <div class="layui-collapse" lay-accordion="">
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">API返回格式</h2>
                            <div class="layui-colla-content">
                                <div class="layui-collapse" lay-accordion="">
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">json</h2>
                                        <div class="layui-colla-content layui-show">
                                            <p><?=$conf['siteurl'];?>/dwz.php?longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">text</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?format=txt&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">二维码</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?format=qrcode&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">API用途类型</h2>
                            <div class="layui-colla-content">
                                <div class="layui-collapse" lay-accordion="">
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">通用链接</h2>
                                        <div class="layui-colla-content layui-show">
                                            <p><?=$conf['siteurl'];?>/dwz.php?type=ty&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">微信专用</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?type=vx&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">ＱＱ专用</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?type=qq&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">API短网址</h2>
                            <div class="layui-colla-content">
                                <p>如带参数的链接，请先urlencode后再进行传值</p>
                                <p>或者直接POST传值。</p>
                                <div class="layui-collapse" lay-accordion="">
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">不缩短</h2>
                                        <div class="layui-colla-content layui-show">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=0&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">多接口</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=1&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">新浪t.cn</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=2&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">腾讯url.cn</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=3&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">百度dwz.cn</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=4&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">suo.im</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=5&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">mrw.so</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=6&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">sohu.gg</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=8&longurl=要防洪的网址</p>
                                        </div>
                                    </div>
                                    <div class="layui-colla-item">
                                        <h2 class="layui-colla-title">w.url.cn</h2>
                                        <div class="layui-colla-content">
                                            <p><?=$conf['siteurl'];?>/dwz.php?dwzapi=9&longurl=要防洪的网址</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space15">
        <div class="layui-col-md6 layui-col-sm6 layui-col-sx12">
            <div class="layui-card">
                <div class="layui-card-header">站点说明</div>
                <div class="layui-card-body">
                    <table class="layui-table" lay-size="sm">
                        <tbody>
                        <tr>
                            <td>网站名称</td>
                            <td class="vip-sitename"><?=$conf['name'];?></td>
                        </tr>
                        <tr>
                            <td>当前版本</td>
                            <td class="vip-version">x.x.x</td>
                        </tr>
                        <tr>
                            <td>前台框架</td>
                            <td class="layui-version">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="layui-col-md6 layui-col-sm6 layui-col-sx12">
            <div class="layui-card">
                <div class="layui-card-header">联系信息</div>
                <div class="layui-card-body">
                    <p>客　服: <a href="../api.php?method=qqtalk&qq=<?=$conf['kfqq'];?>" target="_blank"><?=$conf['kfqq'];?></a></p>
                    <p>邮　箱: <?=$conf['kfqq'];?>@qq.com</p>
                    <p>官　网: <a href="<?=$conf['siteurl'];?>" target="_blank"><?=$conf['siteurl'];?></a></p>
                    <p>官方群: <a href="../api.php?method=joinqun&qun=<?=$conf['qid'];?>" class="layui-btn layui-btn-xs" target="_blank">加入</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 统计.1 TPL -->
<script type="text/html" id="countTpl">
    {{# layui.each(d.list, function(index, item){ }}
    {{# if(item.type == 1){ }}
    <div class="layui-col-md6 layui-col-sm6 layui-col-xs12">
        <div class="vip-card">
            <a class="vip-card-content">
                <p class="layui-elip" style="font-size: 16px;">{{ item.count }}</p>
                <div class="layui-elip">{{ item.title }}</div>
            </a>
        </div>
    </div>
    {{# } }}
    {{# }) }}

</script>
<!-- 公告 TPL -->
<script type="text/html" id="noticeTpl">
    {{# layui.each(d.list, function(index, item){ }}
    {{# if(index>4){return ;} }}
    <li class="layui-elip"><a class="vip-alert-notice-list" href="javascript:;" data-id="{{ item.id }}" data-title="{{ item.title }}" title="{{ item.title }}">
        {{# if(item.type == 1){ }}
        <span class="layui-badge layui-elip">严重</span>
        {{# }else if(item.type == 2){ }}
        <span class="layui-badge layui-bg-orange layui-elip">重要</span>
        {{# }else if(item.type == 3){ }}
        <span class="layui-badge layui-bg-black layui-elip">一般</span>
        {{# }else if(item.type == 4){ }}
        <span class="layui-badge layui-bg-gray layui-elip">忽略</span>
        {{# }else if(item.type == 5){ }}
        <span class="layui-badge layui-bg-green layui-elip">完成</span>
        {{# }else{ }}
        <span class="layui-badge layui-bg-gray layui-elip">···</span>
        {{# } }}
        {{ item.title }}</a></li>
    {{# }) }}
</script>
<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript" src="../assets/user/js/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="../assets/user/js/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/echarts.min.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/theme/style.js"></script>
<script type="text/javascript">
    layui.config({base: '../assets/user/js/'}).use(['app','table','layer','form','qrcode','element'],function(){
        var $ = layui.$
        ,app = layui.app
        ,table = layui.table
        ,layer = layui.layer
        ,form = layui.form
        ,qrcode = layui.qrcode
        ,element = layui.element;

        form.on('submit(create)', function(data) {
            var field = data.field;
            if(!field.type)   return layer.msg('请选择生成类型！');
            if(!field.longurl)   return layer.msg('请输入要生成的网址！');

            $.getJSON('../dwz.php', field, function(json, textStatus) {
                console.log(field);
                if (json.code == 1) {
                    layer.open({
                      type: 1
                      ,area: ['300px', '300px']
                      ,skin: 'layui-layer-demo' //样式类名
                      ,title: '查收防洪链接'
                      ,shade: 0.6 //遮罩透明度
                      ,maxmin: true //允许全屏最小化
                      ,anim: 1 //0-6的动画形式，-1不开启
                      ,shadeClose: true //开启遮罩关闭
                      ,content: '<div style="padding:50px;text-align: center;"><img id="qrcode" width="100px"><br /><br />'+json.ae_url+'</div>'
                    });
                    $('#qrcode').qrcode(json.ae_url);
                    $('#qrcode').attr('src', convertCanvasToImage());
                }else{
                    layer.alert(json.msg);
                }
            });
        });

        // 充值
        form.on('submit(recharge)', function(data){
          var field = data.field;
          if(!field.money)   return layer.msg('充值金额错误！');
          if(!data.elem.value)   return layer.msg('支付方式错误！');

          var loading= layer.msg('请稍候！', { icon: 16 ,shade: 0.01,time: 2000000});
          $.ajax({
              url: 'ajax.php?method=recharge',
              type: 'post',
              dataType: 'json',
              data: field,
              success:function(msg){
                var strJson = JSON.stringify(msg) 
                var d = $.parseJSON(strJson);
                if (d.res == 1) {
                    layer.open({
                      type: 1,
                      title: '订单信息',
                      skin: 'layui-layer-demo', 
                      closeBtn: 0,
                      anim: 2,
                      width: 300,
                      shadeClose: true,
                      content: '<table class="layui-table" lay-size="md" lay-even="" lay-skin="row" style="padding:20px;">\
                        <tbody>\
                        <tr><td>订&nbsp单&nbsp号：</td><td>' + d.trade_no + '</td></tr>\
                        <tr><td>订单金额：</td><td>'+d.money+'元</td></tr>\
                        <tr><td>订单名称：</td><td>'+d.name+'</td></tr>\
                        <tr><td colspan="2">付款后即时到帐，即时生效，无需卡密。</td></tr>\
                        <tr><td colspan="2" class="text-center">手机扫码支付</td></tr>\
                        <tr><td colspan="2" class="text-center"><img id="pay_qrcode"></td></tr>\
                        <tr><td colspan="2"><a href="../includes/bank/submit.php?type='+data.elem.value+'&orderid='+d.trade_no+'" class="layui-btn layui-btn-fluid layui-bg-blue" target="_blank">立即支付</a></td></tr>\
                        </tbody>\
                    </table>'
                    });
                    $('#pay_qrcode').qrcode('<?=$conf["siteurl"];?>/includes/bank/submit.php?type='+data.elem.value+'&orderid='+d.trade_no);
                    $('#pay_qrcode').attr('src', convertCanvasToImage());
                    layer.close(loading);
                }else{
                    layer.msg(d.msg);
                }
                console.log(d);
              },
              error:function(error){
                layer.alert("服务器超时！！");
              }
          });
        });

        // 接口状态
        app.ajaxTpl({
            url: './json.php?method=count1'
            ,where: {}
            ,tpl: '#countTpl'
            ,el: '.vip-dashboard-count'
            ,done: function(){
                //console.log('count.1 is done');
            }
        });

        // 公告 1.3.0
        app.noticePanel({
            url: './json.php?method=notice'
            //,where: {}
            ,tpl: '#noticeTpl'
            ,el: '.vip-notice-box'
            ,done: function(){
                //console.log('noticePanel is done');
            }
        });
        // 公告内容 1.3.0
        $(document).on('click','.vip-alert-notice-list',function(){
            var id = $(this).data('id');
            $.post('./json.php?method=notice-panel',{id:id},function(res){
                console.log(res);
                if(res.code == 0){
                    app.notice(res.data.content,function(){
                        location.href = res.data.href;
                    });
                }else{
                    app.msg('出错了');
                }
            },'json');
        });

        // 子窗口点击按钮打开页面:方式一
        $(document).on('click','[vip-href]',function(){
            parent.appHref(this);
        });

        // 赋值-版本号/内置LAYUI版本
        app.val(['.vip-version','.layui-version'],['v<?=VERSION;?>',app.layVersion]);
        //$('.vip-version').html(app.version+' / '+app.updateTime);
        //$('.layui-version').html(app.layVersion);

        // 监听框架变化
        $(window).on('resize',function(){
            
        });
        function convertCanvasToImage() {  
            var canvas=document.getElementsByTagName('canvas')[0];
            var image = new Image();  
            data = canvas.toDataURL("image/jpeg");  
            return data;  
        }
    });
</script>
</body>
</html>