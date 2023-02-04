<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>数据详情</title>
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
            <div class="layui-card">
                <div class="layui-card-header">数据报表</div>
                <div class="layui-card-body">

                    <div class="layui-row vip-btn-tool-box">
                        <div class="layui-form-item layui-btn-group">
                            <button type="button" class="layui-btn layui-btn-normal" id="7day">近7日数据</button>
                            <button type="button" class="layui-btn layui-btn-primary" id="15day">近15日数据</button>
                            <button type="button" class="layui-btn layui-btn-primary" id="30day">近30日数据</button>
                        </div>
                    </div>

                    <div id="table" lay-filter="table"></div>
                </div>
            </div>
        </div>
        <!--div class="layui-col-md12"><blockquote class="layui-elem-quote mar-no">模板风格</blockquote></div-->
    </div>
</div>

<script type="text/html" id="menu">
    <button class="layui-btn layui-btn-xs layui-bg-blue" lay-event="overview">图表</button>
    <button class="layui-btn layui-btn-xs layui-bg-black" lay-event="visitor">明细</button>
</script>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['layer','table','form','app','laydate','element'],function(){
    var $ = layui.$
    , layer = layui.layer
    , table = layui.table
    , form = layui.form
    , app = layui.app
    , laydate = layui.laydate 
    , element = layui.element;
    //执行一个laydate实例

    // 表格
    var dataTable = table.render({
        elem: '#table'
        ,id: 'table'
        ,url: './json.php?'    // 数据接口
        ,where: {method: 'detail-data', uid: top.location.uid}
        //,page:{theme: '#1E9FFF'}         // 开启分页
        ,loading: true                     // 开启loading
        ,cellMinWidth: 80                  // 每列最小宽度
        ,limits: [7,15,30]                 // 每页条数的选择项
        ,limit: 7                          // 默认每页条数
        ,toolbar: 'false'                  // 开启表格头部工具栏区域
        ,defaultToolbar: ['exports', 'filter', 'print'] // 自由配置头部工具栏右侧的图标 
        ,totalRow: true
        ,cols: [[                           // 表头
            {type: 'checkbox', fixed: 'id'}
            ,{field: 'longurl', title: '源站地址', totalRowText: '合计'}
            ,{field: 'date', title: '日期', width:110, align:'center'}
            ,{field: 'pv', title: '浏览量(PV)', width:110, align:'center', totalRow: true}
            ,{field: 'uv', title: '访客量(UV)', width:110, align:'center', totalRow: true}
            ,{field: 'ip', title: '访问量(IP)', width:110, align:'center', totalRow: true}
            ,{title: '操作', fixed: 'right',width:120, align:'center', templet:'#menu'}
        ]]
    });

    // 监听表格刷新
    $('#7day').on('click',function(){
        $('#7day').attr('class',"layui-btn layui-btn-normal");
        $('#15day').attr('class',"layui-btn layui-btn-primary");
        $('#30day').attr('class',"layui-btn layui-btn-primary");
        table.reload('table', {
          where: {startDate: fun_date(-7)}
        });
    });
    $('#15day').on('click',function(){
        $('#7day').attr('class',"layui-btn layui-btn-primary");
        $('#15day').attr('class',"layui-btn layui-btn-normal");
        $('#30day').attr('class',"layui-btn layui-btn-primary");
        table.reload('table', {
          where: {startDate: fun_date(-15)}
        });
    });
    $('#30day').on('click',function(){
        $('#7day').attr('class',"layui-btn layui-btn-primary");
        $('#15day').attr('class',"layui-btn layui-btn-primary");
        $('#30day').attr('class',"layui-btn layui-btn-normal");
        table.reload('table', {
          where: {startDate: fun_date(-30)}
        });
    });
    //监听工具条
    table.on('tool(table)', function(obj){
        var data = obj.data;            //获得当前行数据
        var layEvent = obj.event;       //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        top.location.querydate = data.date;
        if(layEvent === 'overview'){
            parent.appHrefApi('访问概况','/other/overview.php');
        } else if(layEvent === 'visitor'){
            parent.appHrefApi('访问明细','/other/detail-visitor.php');
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