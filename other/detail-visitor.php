<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>访问明细</title>
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
                <div class="layui-card-header">数据详情</div>
                <div class="layui-card-body">
                    <div class="layui-row vip-btn-tool-box">
                        <div class="layui-form-item layui-btn-group">
                            <button type="button" class="layui-btn layui-btn-primary" id="today">今日数据</button>
                            <button type="button" class="layui-btn layui-btn-primary" id="yesterday">昨日数据</button>
                        </div>
                        <div class="layui-form-item layui-inline">
                            <input type="text" class="layui-input" id="queryday">
                        </div>
                    </div>

                    <div id="table" lay-filter="table"></div>
                </div>
            </div>
        </div>
        
    </div>
</div>

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

    laydate.render({
      elem: '#queryday'
      ,value: new Date()
      ,done: function(value, date, endDate){
        $('#today').attr('class',"layui-btn layui-btn-primary");
        $('#yesterday').attr('class',"layui-btn layui-btn-primary");
        queryData(top.location.uid,value);
      }
    });

    if (top.location.querydate == fun_date(0)) {
        $('#today').attr('class',"layui-btn layui-btn-normal");
    }else if (top.location.querydate == fun_date(-1)) {
        $('#yesterday').attr('class',"layui-btn layui-btn-normal");
    }else{
        $('#queryday').val(top.location.querydate);
    }

    queryData(top.location.uid,top.location.querydate);

    $('#today').on('click',function(){
        $('#today').attr('class',"layui-btn layui-btn-normal");
        $('#yesterday').attr('class',"layui-btn layui-btn-primary");
        table.reload('table', {
          where: {startDate: fun_date(0),endDate: fun_date(0)}
        });
    });
    $('#yesterday').on('click',function(){
        $('#today').attr('class',"layui-btn layui-btn-primary");
        $('#yesterday').attr('class',"layui-btn layui-btn-normal");
        table.reload('table', {
          where: {startDate: fun_date(-1),endDate: fun_date(-1)}
        });
    });
    function queryData(uid,date) {
        table.render({
            elem: '#table'
            ,id: 'table'
            ,url: './json.php?'
            ,where: {
                method: 'detail-visitor'
                , uid: uid
                , startDate: date
                , endDate: date
            }
            ,page:{theme: '#1E9FFF'}            // 开启分页
            ,loading: true                      // 开启loading
            ,cellMinWidth: 80                   // 每列最小宽度
            ,limits: [15,30,50]                 // 每页条数的选择项
            ,limit: 7                          // 默认每页条数
            ,toolbar: 'false'                 // 开启表格头部工具栏区域 该参数为 layui 2.4.0 开始新增。
            ,defaultToolbar: ['exports', 'filter', 'print'] // 自由配置头部工具栏右侧的图标 该参数为 layui 2.4.1 新增
            ,cols: [[                           // 表头
                {type: 'checkbox', fixed: 'id'}
                ,{field: 'time', title: '访问时间', width:180, align:'center'}
                ,{field: 'ip_address', title: '访问ＩＰ', width:140, align:'center'}
                ,{field: 'cityName', title: '访问地区', width:110, align:'center'}
                ,{field: 'referer', title: '访问来路'}
                ,{field: 'brand', title: '访问设备', width:120, align:'center'}
                ,{field: 'system', title: '访问系统', width:100, align:'center'}
                ,{field: 'browser', title: '访问浏览器', width:100, align:'center'}
                ,{field: 'browserVersion', title: '浏览器版本', width:130, align:'center'}
            ]]
        });
    }
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