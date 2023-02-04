<?php
    @header('Content-Type: text/html; charset=UTF-8');
    include('../includes/init.php');
    if ($user_login == 0) exit("<script language='javascript'>alert('请重新登录！');window.location.href='./login.php';</script>");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title><?=$conf['name'];?> - 用户系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body id="VIP_body" ontouchstart="">

<!-- 应用 -->
<div id="VIP_app">

    <!-- 标题 title -->
    <div class="vip-title user-select">Template</div><!-- active -->

    <!-- 导航 nav -->
    <div class="vip-nav user-select">

        <ul class="vip-nav-user">
            <li>
                <i class="vip-icon is-sub-nav" vip-title="用户" title="用户">&#xe61c;</i>
                <div class="vip-sub-nav layui-anim vip-anim-left vip-sub-nav-bottom">
                    <div class="layui-card">
                        <h1 class="layui-card-header layui-elip"><?=$udata['username'];?>,您好！</h1>
                        <div class="layui-card-body">

                            <div class="layui-row layui-show">
                                <div class="layui-col-xs12 layui-elip" vip-href="bill.php" title="收支明细"><i class="vip-icon">&#xe74f;</i> 收支明细</div>
                                <div class="layui-col-xs12 layui-elip" vip-href="user.php" title="个人中心"><i class="vip-icon">&#xe61c;</i> 个人中心</div>
                                <div class="layui-col-xs12 layui-elip" vip-href="pass.php" title="更改密钥"><i class="vip-icon">&#xe669;</i> 更改密钥</div>
                            </div>

                        </div>
                    </div>
                </div>
            </li>
            <!--<li>
                <i class="vip-icon is-sub-nav" vip-title="主题" title="主题">&#xe789;</i>
                <div class="vip-sub-nav layui-anim vip-anim-left vip-sub-nav-bottom">
                    <div class="layui-card">
                        <h1 class="layui-card-header layui-elip">主题</h1>
                        <div class="layui-card-body">

                            <div class="layui-row layui-show vip-theme">

                            </div>

                        </div>
                    </div>
                </div>
            </li>-->
            <li>
                <i class="vip-icon vip-msg" vip-title="通知" title="您有 9+ 条通知" onclick="layer.msg('暂未开放！');">&#xe642;</i>
                <span class="layui-badge-dot"></span>
                <div class="vip-sub-nav-tips layui-anim vip-anim-left vip-sub-nav-bottom">您有 9+ 条通知</div>
            </li>
            <li>
                <i class="vip-icon vip-out" vip-title="退出" title="退出">&#xe622;</i>
                <div class="vip-sub-nav-tips layui-anim vip-anim-left vip-sub-nav-bottom">退出</div>
            </li>
        </ul>

        <div class="vip-nav-btn">
            <div class="width-height-all">
                <ul>

                </ul>
            </div>
        </div>

        <div class="vip-nav-logo">

        </div>

    </div>

    <!-- 内容 content -->
    <iframe class="vip-body"></iframe>

    <!-- 右侧选项卡 aside-tab -->
    <div class="vip-aside user-select">

    </div>

    <!-- 控制面板 -->
    <div class="vip-control vip-anim-left">

        <!-- 设置 -->
        <div class="layui-card">
            <div class="layui-card-header">设置</div>
            <div class="layui-card-body">
                <div class="layui-form layui-form-pane">

                    <div class="layui-form-item" pane>
                        <label class="layui-form-label">清理缓存</label>
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-xs vip-clear-btn">点击清理</button>
                        </div>
                    </div>

                    <div class="layui-form-item" pane>
                        <label class="layui-form-label">全屏模式</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="fullScreen" lay-filter="switch-full-screen" lay-skin="switch" lay-text="开启|关闭">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 主题 -->
        <div class="layui-card">
            <div class="layui-card-header">主题</div>
            <div class="layui-card-body pad-no">
                <div class="layui-row vip-theme">

                </div>
            </div>
        </div>

        <!-- 版本 -->
        <div class="layui-card">
            <div class="layui-card-header">版本</div>
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

        <!-- 评分 -->
        <div class="layui-card">
            <div class="layui-card-header">评分</div>
            <div class="layui-card-body">
                <div id="controlRateBase"></div>
            </div>
        </div>

    </div>

    <!-- 滑动操作面板 touch panel -->
    <div class="vip-touch"></div>

</div>

<!-- 右侧选项卡TPL aside-tab TPL -->
<script type="text/html" id="tabTpl">
    <ul>
        {{#  layui.each(d.list, function(index, item){ }}
            {{# if(item.now == 1){ }}
            <li class="layui-elip vip-home active" vip-href="{{ item.href }}" title="{{ item.title }}">{{ item.title }}</li>
            {{# }else { }}
            <li class="layui-elip vip-home" vip-href="{{ item.href }}" title="{{ item.title }}">{{ item.title }}</li>
            {{# } }}
        {{# }) }}
        <hr class="layui-bg-black" />
        {{# if(d.isCtr==1){ }}
        <li class="vip-saide-btn vip-control-btn active-bg" title="设置">
        {{# }else{ }}
        <li class="vip-saide-btn vip-control-btn" title="设置">
        {{# } }}
            <i class="vip-icon">&#xe744;</i>
        </li>
        <li class="vip-saide-btn vip-refresh" title="刷新">
            <i class="vip-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe68a;</i>
        </li>
        <li class="vip-saide-btn vip-close" title="关闭">
            <i class="vip-icon">&#xe624;</i>
        </li>
        <li class="vip-saide-btn vip-other-close" title="关闭其他">
            <i class="vip-icon">&#xe66e;</i>
        </li>
    </ul>
</script>
<!-- logoTPl -->
<script type="text/html" id="logoTpl">
    <a href="javascript:;" vip-href="{{ d.href }}" vip-title="{{ d.title }}" title="{{ d.title }}"><img src="{{ d.logo }}" alt="{{ d.title }}" /></a>
</script>
<!-- 主导航TPL nav TPL -->
<script type="text/html" id="navTpl">
    {{#  layui.each(d.list, function(index, item){ }}
    <li>
        {{# if(!item.list){ }}
        <i class="vip-icon" vip-href="{{ item.href }}" vip-title="{{ item.title }}" title="{{ item.title }}">{{ item.icon }}</i>
        <div class="vip-sub-nav-tips layui-anim vip-anim-left">{{ item.title }}</div>
        {{# }else{ }}
        <i class="vip-icon is-sub-nav" vip-title="{{ item.title }}" title="{{ item.title }}">{{ item.icon }}</i>
        {{# if(d.cfg.width){ }}
            <div class="vip-sub-nav layui-anim vip-anim-left" style="width: {{ d.cfg.width }}px">
        {{# }else{ }}
            <div class="vip-sub-nav layui-anim vip-anim-left">
        {{# } }}
            <div class="layui-card">
                <h1 class="layui-card-header layui-elip">{{ item.title }}</h1>
                <div class="layui-card-body">
                    {{# if(item.list[0].list){ }}
                        <span>
                            {{#  layui.each(item.list, function(k, v){ }}
                                {{# if(k == 0){ }}
                                <i class="vip-icon active" title="{{ v.title }}">{{ v.icon }}</i>
                                {{# }else{ }}
                                <i class="vip-icon" title="{{ v.title }}">{{ v.icon }}</i>
                                {{# } }}
                            {{# }) }}
                        </span>
                        {{#  layui.each(item.list, function(k, v){ }}
                            {{# if(k == 0){ }}
                            <div class="layui-row layui-show">
                            {{# }else{ }}
                            <div class="layui-row">
                            {{# } }}
                                {{#  layui.each(v.list, function(kk, vv){ }}
                                <div class="layui-col-xs{{ d.cfg?d.cfg.ratio:d.cfg.ratio }} layui-elip" vip-href="{{ vv.href }}" vip-title="{{ vv.title }}" vip-blank="{{ vv.blank||'' }}" title="{{ vv.title }}">
                                    {{# if(d.cfg.icon == true){ }}
                                    <i class="vip-icon">{{ vv.icon }}</i>
                                    {{# } }}
                                    {{ vv.title }}</div>
                                {{# }) }}
                            </div>
                        {{# }) }}
                    {{# }else{ }}
                        <div class="layui-row layui-show">
                            {{#  layui.each(item.list, function(kk, vv){ }}
                            <div class="layui-col-xs{{ d.cfg?d.cfg.ratio:d.cfg.ratio }} layui-elip" vip-href="{{ vv.href }}" vip-title="{{ vv.title }}" vip-blank="{{ vv.blank||'' }}" title="{{ vv.title }}">
                                {{# if(d.cfg.icon == true){ }}
                                <i class="vip-icon">{{ vv.icon }}</i>
                                {{# } }}
                                {{ vv.title }}</div>
                            {{# }) }}
                        </div>
                    {{# } }}
                </div>
            </div>
        </div>
        {{# } }}
    </li>
    {{#  }) }}
</script>
<!-- 主题 theme -->
<script type="text/html" id="themeTpl">
    {{#  layui.each(d.list, function(index, item){ }}
    <div class="layui-col-xs{{ d.cfg.ratio||6 }} layui-elip {{ item.class }}" title="{{ item.title }}" vip-theme="{{ item.skin }}">{{ item.title }}</div>
    {{#  }); }}
</script>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['app','vipPush','form','layer','colorpicker','slider','rate','cookie'],function(){
    var $ = layui.$
    , app = layui.app
    , vipPush = layui.vipPush
    , form = layui.form
    , layer = layui.layer
    , colorpicker = layui.colorpicker
    , slider = layui.slider
    , rate = layui.rate
    , cookie = layui.cookie;
    // 初始化配置 init
    app.init({
        logo: '../assets/user/img/web/logo.png'
        ,title: '<?=$conf['name'];?>'
        ,bodyTitle: '仪表板'
        ,bodyHref: 'dashboard.php'
        //,method: 'post'
        ,loading: '<div class="sk-rotating-plane mar-no"></div>'
        ,scrollTopVal: 10
        ,asideMaxNum: 10
        ,isTitleShow: true
        //,themeSrc: '../assets/user/css/theme/'          // 1.2.1新增
        ,touchTitle: '触控面板(左右滑动单击)'     // 1.2.1新增
        ,out: function(){
            layer.confirm('确定退出了?', {title:'提示'}, function(index){
                layer.close(index);
                app.msg('慢走，不送了你嘞');
                $.cookie('user_token', '',{path:'/'});
                location.pathname = '/user/login.php';
            });
        }
    });

    // 主题 theme
    app.theme({
        cfg:{
            ratio: 6                // 1.3.5新增
            ,src: '../assets/user/css/theme/'   // 1.2.1新增
        }
        ,list:[
            {skin:'default',title:'默认',class:'layui-bg-gray'}
            ,{skin:'black',title:'黑色',class:'layui-bg-black'}
            ,{skin:'green',title:'绿色',class:'layui-bg-green'}
            ,{skin:'orange',title:'橙色',class:'layui-bg-orange'}
            ,{skin:'blue',title:'蓝色',class:'layui-bg-blue'}
            ,{skin:'red',title:'红色',class:'layui-bg-red'}
        ]
        ,tpl: '#themeTpl'
        ,el: '.vip-theme'
    });

    // 导航菜单 nav
    app.nav({
        cfg:{
            width: 250      // 1.3.5修改
            ,ratio: 6       // 1.3.5新增
            ,icon: true     // 1.3.5新增
        }
        ,url: './json.php?method=nav'
        ,where: {}
        ,tpl: '#navTpl'
        ,el: '.vip-nav .vip-nav-btn ul'
    });

    // logo
    app.logo({
        logo: '../assets/user/img/web/logo.png'
        ,title: '仪表板'
        ,href: 'dashboard.php'
        ,tpl: '#logoTpl'
        ,el: '.vip-nav-logo'
    });

    /*// 广告插件 push
    vipPush.render({
        url: '../json/demo-push.json'
        ,stylesheet: '../css/vipPush.css'
        ,title: '推荐'
    });*/

    /* 设置面板-其他示例 --------开始--------- */
    // 颜色选择器
    colorpicker.render({
        elem: '#controlColorPicker'
        ,color: '#FFF'
        ,done: function(color){
            app.msg(color);
        }
    });

    // 渲染滑块
    slider.render({
        elem: '#slideTest'  // 绑定元素
        ,tips: true
        ,change: function(value){
            console.log(value); // 动态获取滑块数值
            //do something
        }
    });

    // select选择器
    form.on('select(vip-control-select)', function(data){
        //console.log(data.elem);       // 得到select原始DOM对象
        //console.log(data.value);      // 得到被选中的值
        //console.log(data.othis);      // 得到美化后的DOM对象
        app.msg(data.value);
    });
    /* 设置面板-其他示例 --------结束--------- */


    // 赋值-版本号/内置LAYUI版本
    app.val(['.vip-version','.layui-version'],['v<?=VERSION;?>',app.layVersion]);

    // 评分
    rate.render({
        elem: '#controlRateBase'
        ,choose: function(value){
            if(value > 4){
                app.msg('么么哒');
            }
        }
    });

});
</script>
</body>
</html>