<?php
    include('./includes/init.php');
    $row = $DB->get_row('select uid,longurl from `uomg_log` where uid = "'.$_GET['keyword'].'" or longurl="'.$_GET['keyword'].'";');
    if (empty($row)) {
      sysmsg('没找到该条数据！');
    }
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title><?=$conf['name'];?> - 统计查询</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="icon" href="/assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="/assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="/assets/user/css/app.css"/>
<body id="VIP_body" ontouchstart="">

<!-- 应用 -->
<div id="VIP_app">

    <!-- 标题 title -->
    <div class="vip-title user-select">Template</div><!-- active -->

    <!-- 导航 nav -->
    <div class="vip-nav user-select">

        <ul class="vip-nav-user">
            <li>
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
            </li>
            <li>
              <i class="vip-icon vip-kefugoto" vip-href="../api.php?method=qqtalk&qq=<?=$conf['kfqq'];?>" vip-title="客服" title="客服">&#xe627;</i>
              <div class="vip-sub-nav-tips layui-anim vip-anim-left vip-sub-nav-bottom">在线客服</div>
            </li>
            <li>
                <i class="vip-icon vip-msg" vip-title="通知" title="您有 9+ 条通知" onclick="layer.msg('暂未开放！');">&#xe642;</i>
                <span class="layui-badge-dot"></span>
                <div class="vip-sub-nav-tips layui-anim vip-anim-left vip-sub-nav-bottom">您有 9+ 条通知</div>
            </li>

        </ul>

        <div class="vip-nav-btn">
            <div class="width-height-all">
              <ul>  
                <li>  
                  <i class="vip-icon" vip-href="/other/detail-data.php" vip-title="数据报表" title="数据详情">&#xe660;</i> 
                  <div class="vip-sub-nav-tips layui-anim vip-anim-left">数据报表</div>
                </li>
                <li>  
                  <i class="vip-icon" vip-href="/other/detail-visitor.php" vip-title="访问明细" title="访问明细">&#xe631;</i> 
                  <div class="vip-sub-nav-tips layui-anim vip-anim-left">访问明细</div>
                </li>
                <li>  
                  <i class="vip-icon" vip-href="/other/overview.php" vip-title="数据概况" title="数据概况">&#xe700;</i> 
                  <div class="vip-sub-nav-tips layui-anim vip-anim-left">数据概况</div>
                </li>
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
<!-- 主题 theme -->
<script type="text/html" id="themeTpl">
    {{#  layui.each(d.list, function(index, item){ }}
    <div class="layui-col-xs{{ d.cfg.ratio||6 }} layui-elip {{ item.class }}" title="{{ item.title }}" vip-theme="{{ item.skin }}">{{ item.title }}</div>
    {{#  }); }}
</script>

<script type="text/javascript" src="/assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '/assets/user/js/'}).use(['app','vipPush','form','layer','colorpicker','slider','rate','cookie'],function(){
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
    app.delData('app',null);

    location.uid = '<?=$row['uid'];?>';
    location.querydate = fun_date(0);

    app.init({
        logo: '/assets/user/img/web/logo.png'
        ,title: '<?=$conf['name'];?>'
        ,bodyTitle: '数据报表'
        ,bodyHref: '/other/detail-data.php'
        //,method: 'post'
        ,loading: '<div class="sk-rotating-plane mar-no"></div>'
        ,scrollTopVal: 10
        ,asideMaxNum: 10
        ,isTitleShow: true
        //,themeSrc: '/assets/user/css/theme/'          // 1.2.1新增
        ,touchTitle: '触控面板(左右滑动单击)'     // 1.2.1新增
        ,out: function(){

        }
    });

    // 主题 theme
    app.theme({
        cfg:{
            ratio: 6                // 1.3.5新增
            ,src: '/assets/user/css/theme/'   // 1.2.1新增
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

    // logo
    app.logo({
        logo: '/assets/user/img/web/logo.png'
        ,title: '数据报表'
        ,href: '/other/detail-data.php'
        ,tpl: '#logoTpl'
        ,el: '.vip-nav-logo'
    });

    /* 设置面板-其他示例 --------开始--------- */
    // 颜色选择器
    colorpicker.render({
        elem: '#controlColorPicker'
        ,color: '#FFF'
        ,done: function(color){
            app.msg(color);
        }
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

    function fun_date(num) { 
      var date = new Date();
      date.setDate(date.getDate() + num);
      var year = date.getFullYear()
      , month = date.getMonth() + 1
      , strDate = date.getDate();

      if (month >= 1 && month <= 9) {
          month = "0" + month;
      }
      if (strDate >= 0 && strDate <= 9) {
          strDate = "0" + strDate;
      }
      return currentdate = year + "-" + month + "-" + strDate;
    }
});
</script>
</body>
</html>