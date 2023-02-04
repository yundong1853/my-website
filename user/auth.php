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
    <title>授权列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css" media="all"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <blockquote class="layui-elem-quote mar-no">
                <span class="layui-breadcrumb">
                  <a onclick="parent.appHrefApi('仪表板','dashboard.php');">首页</a>
                  <a><cite>授权管理</cite></a>
                </span>
            </blockquote>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">工具栏表格</div>
                <div class="layui-card-body">

                    <!-- 表格工具栏 -->
                    <div class="layui-row vip-btn-tool-box">
                        <div class="layui-col-sm12 layui-col-sm4 layui-col-md4 layui-btn-container">
                            <button class="layui-btn layui-btn-radius layui-btn-primary" id="vip-btn-add" title="新增"><i class="vip-icon">&#xe65c;</i></button>
                            <button class="layui-btn layui-btn-radius layui-btn-primary" id="vip-btn-refresh" title="刷新"><i class="vip-icon">&#xe68a;</i></button>
                        </div>
                    </div>

                    <div id="demo-table-2" lay-filter="table"></div>
                </div>
            </div>
        </div>
        <!--div class="layui-col-md12"><blockquote class="layui-elem-quote mar-no">模板风格</blockquote></div-->
        <?php 
            /*foreach($tpls as $key=>$value){
        ?>
        <div class="layui-col-md3 layui-col-sm6">
            <div class="vip-goods-list">
                <img src="<?=$value['img']; ?>" style="height: 140px" alt="<?=$value['name'];?>">
                <div class="vip-goods-title text-center"><?=$value['desc'];?></div>
                <div class="vip-goods-footer text-center">
                    <span class="vip-goods-price text-sx text-pink"><?=$value['name'];?></span>
                </div>
            </div>
        </div>
        <?php 
        }
        unset($tpls,$tplnums,$value,$key,$tf);*/
        ?>
    </div>
</div>

<script type="text/html" id="menu">
    <button class="layui-btn layui-btn-xs layui-bg-blue" lay-event="ren">续费</button>
    <button class="layui-btn layui-btn-xs layui-bg-cyan" lay-event="edit">编辑</button>
    <button class="layui-btn layui-btn-xs layui-bg-red" lay-event="del">删除</button>
</script>
<script type="text/html" id="status">
    {{# if(d.end_time <= '<?=$date;?>'){ }}
        {{# return '<span class="layui-badge layui-bg-orange">已过期</span>'; }}
    {{# } }}
    {{# if(d.status == 0){ }}
    <span class="layui-badge layui-bg-gray">待审核</span>
    {{# }else if(d.status == 1){ }}
    <span class="layui-badge layui-bg-green">白名单</span>
    {{# }else if(d.status == 2){ }}
    <span class="layui-badge layui-bg-orange">黑名单</span>
    {{# }else{ }}
    <span class="layui-badge layui-bg-orange">···</span>
    {{# } }}
</script>
<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['layer','table','form','app','element'],function(){
    var $ = layui.$
    , layer = layui.layer
    , table = layui.table
    , form = layui.form
    , app = layui.app
    , element = layui.element;

    // 表格
    var table2 = table.render({
        elem: '#demo-table-2'
        ,id: 'table-id-2'
        ,url: './json.php?method=auth'   // 数据接口
        ,page: true                         // 开启分页
        ,loading: true                      // 开启loading
        ,cellMinWidth: 80                   // 每列最小宽度
        ,limits: [15,30,50]                 // 每页条数的选择项
        ,limit: 15                          // 默认每页条数
        ,toolbar: 'default'                 // 开启表格头部工具栏区域 该参数为 layui 2.4.0 开始新增。
        ,defaultToolbar: ['exports', 'filter', 'print'] // 自由配置头部工具栏右侧的图标 该参数为 layui 2.4.1 新增
        ,cols: [[                           // 表头
            {type: 'checkbox', fixed: 'id'}
            ,{field: 'domain', title: '授权域名'}
            ,{title: '状态', sort: true,templet:'#status',align:'center'}
            ,{field: 'ios_qq', title: '苹果 QQ跳转风格'}
            ,{field: 'an_qq', title: '安卓 QQ跳转风格'}
            ,{field: 'ios_vx', title: '苹果 VX跳转风格'}
            ,{field: 'an_vx', title: '安卓 VX跳转风格'}
            ,{field: 'other', title: '其他浏览器风格'}
            ,{field: 'add_time', title: '添加时间'}
            ,{field: 'end_time', title: '到期时间'}
            ,{title: '操作', fixed: 'right',width:160,align:'center', templet:'#menu'}
        ]]
    });

    // 监听新增按钮
    $('#vip-btn-add').on('click',function(){
        parent.appHrefApi('添加授权','auth-add.php');
        //app.hrefApi('添加授权','auth-add.php');
    });

    // 监听多选删除按钮
    $('#vip-btn-del').on('click',function(){
        var checkStatus = table.checkStatus('table-id-2'); // table-id-2 即为基础参数id对应的值

        if(!checkStatus.data.length){
            app.msg('未选中数据');
            return false;
        }

        if(checkStatus.isAll){
            app.msg('全选了:'+ app.getIds(checkStatus.data) );
        }else{
            app.msg( app.getIds(checkStatus.data) );
        }

        console.log(checkStatus.data);
        //console.log(checkStatus.data);        //获取选中行的数据
        //console.log(checkStatus.data.length); //获取选中行数量，可作为是否有选中行的条件
        //console.log(checkStatus.isAll );      //表格是否全选
    });

    // 监听表格刷新
    $('#vip-btn-refresh').on('click',function(){
        // 重载方式 1
        table2.reload();
        // 重载方式 2
        // table.reload('table-id-2', {})
    });

    //监听工具条
    table.on('tool(table)', function(obj){
        var data = obj.data;            //获得当前行数据
        var layEvent = obj.event;       //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;                //获得当前行 tr 的DOM对象
        console.log(data);
        if(layEvent === 'ren'){ // 续费
            parent.appHrefApi('续费授权','auth-ren.php?id='+data.id);
            //app.msg('您点击了查看');
            // do somehing

        }else if(layEvent === 'edit'){ // 编辑
            parent.appHrefApi('修改授权','auth-edit.php?id='+data.id);
            //app.msg('您点击了编辑');
            // do somehing

        } else if(layEvent === 'del'){ //删除

            layer.confirm('真的删除行么', function(index){
                obj.del(); // 删除对应行（tr）的DOM结构
                layer.close(index);
                // do somehing
            });

        }
    });
});
</script>
</body>
</html>