<?php
    include('../includes/init.php');
    $row = $DB->query('select a.usetime,ifnull(b.money,0) as money
from (
    SELECT curdate() as usetime
    union all
    SELECT date_sub(curdate(), interval 1 day) as usetime
    union all
    SELECT date_sub(curdate(), interval 2 day) as usetime
    union all
    SELECT date_sub(curdate(), interval 3 day) as usetime
    union all
    SELECT date_sub(curdate(), interval 4 day) as usetime
    union all
    SELECT date_sub(curdate(), interval 5 day) as usetime
    union all
    SELECT date_sub(curdate(), interval 6 day) as usetime
) a left join (
  select date(usetime) as datetime, round(IFNULL(sum(money),0),2) as money
  from uomg_bill where uid='.$udata['id'].' and type=1
  group by date(usetime)
) b on a.usetime = b.datetime;');
    $C = array();
    while($res = $DB->fetch($row)){
        $C[] = $res;
    }
    
        $row = $DB->query('select a.usetime,ifnull(b.money,0) as money
    from (
        SELECT curdate() as usetime
        union all
        SELECT date_sub(curdate(), interval 1 day) as usetime
        union all
        SELECT date_sub(curdate(), interval 2 day) as usetime
        union all
        SELECT date_sub(curdate(), interval 3 day) as usetime
        union all
        SELECT date_sub(curdate(), interval 4 day) as usetime
        union all
        SELECT date_sub(curdate(), interval 5 day) as usetime
        union all
        SELECT date_sub(curdate(), interval 6 day) as usetime
    ) a left join (
      select date(usetime) as datetime, round(IFNULL(sum(money),0),2) as money
      from uomg_bill where  uid='.$udata['id'].' and type=0
      group by date(usetime)
    ) b on a.usetime = b.datetime;');
    $X = array();
    while($res = $DB->fetch($row)){
        $X[] = $res;
    }
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>收支明细</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <link rel="icon" href="../assets/user/img/web/web.ico"/>
    <link rel="stylesheet" href="../assets/user/layui/css/layui.css"/>
    <link rel="stylesheet" href="../assets/user/css/app.css"/>
<body ontouchstart="">

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">

        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">折线图</div>
                <div class="layui-card-body">
                    <div class="vip-demo-box" id="consume_chart"></div>
                </div>
            </div>
        </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">账单明细</div>
                <div class="layui-card-body">

                    <!-- 表格工具栏 -->
                    <div class="layui-row vip-btn-tool-box">
                        <div class="layui-col-sm12 layui-col-sm4 layui-col-md4 layui-btn-container">
                            <button class="layui-btn layui-btn-radius layui-btn-primary" id="vip-btn-refresh" title="刷新"><i class="vip-icon">&#xe68a;</i></button>
                        </div>
                    </div>

                    <div id="consume_table" lay-filter="table"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/echarts.min.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/theme/style.js"></script>
<script type="text/javascript">
layui.config({base: '../assets/user/js/'}).use(['layer','table','form','app'],function(){
    var $ = layui.$
    , layer = layui.layer
    , table = layui.table
    , form = layui.form
    , app = layui.app;

    var chart = echarts.init(document.getElementById('consume_chart'),'style');
    option = {
        title: {
            text: '账户流水单'
        },
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data:['支出金额','充值金额']
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : [
                '<?=date("m-d",strtotime("-6 day"));?>'
                ,'<?=date("m-d",strtotime("-5 day"));?>'
                ,'<?=date("m-d",strtotime("-4 day"));?>'
                ,'<?=date("m-d",strtotime("-3 day"));?>'
                ,'<?=date("m-d",strtotime("-2 day"));?>'
                ,'<?=date("m-d",strtotime("-1 day"));?>'
                ,'<?=date("m-d",time());?>'
                ]
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'支出金额',
                type:'line',
                stack: '总量',
                areaStyle: {normal: {}},
                data:[<?=$X[6]['money'];?>, <?=$X[5]['money'];?>, <?=$X[4]['money'];?>, <?=$X[3]['money'];?>, <?=$X[2]['money'];?>, <?=$X[1]['money'];?>, <?=$X[0]['money'];?>]
            },
            {
                name:'充值金额',
                type:'line',
                stack: '总量',
                areaStyle: {normal: {}},
                data:[<?=$C[6]['money'];?>, <?=$C[5]['money'];?>, <?=$C[4]['money'];?>, <?=$C[3]['money'];?>, <?=$C[2]['money'];?>, <?=$C[1]['money'];?>, <?=$C[0]['money'];?>]
            },
        ]
    };
    chart.setOption(option);


    // 表格
    var consume_table = table.render({
        elem: '#consume_table'
        ,id: 'consume_table'
        ,url: './json.php?method=bill'   // 数据接口
        ,page: true                         // 开启分页
        ,loading: true                      // 开启loading
        ,cellMinWidth: 80                   // 每列最小宽度
        ,limits: [15,30,50]                 // 每页条数的选择项
        ,limit: 15                          // 默认每页条数
        ,toolbar: true                 // 开启表格头部工具栏区域 该参数为 layui 2.4.0 开始新增。
        ,defaultToolbar: ['exports', 'filter', 'print'] // 自由配置头部工具栏右侧的图标 该参数为 layui 2.4.1 新增
        ,cols: [[                           // 表头
            {field: 'money', title: '金额'}
            ,{field: 'type', title: '类型'
                ,templet: function(d){
                    if (d.type == '0') {
                        return '<span class="layui-badge layui-bg-green">消费</span>';
                    }else if (d.type == '1') {
                        return '<span class="layui-badge layui-bg-blue">充值</span>';
                    }
                    
                }}
            ,{field: 'usetime', title: '操作时间',width:160}
            ,{field: 'depict', title: '描述'}
        ]]
    });

    // 监听表格刷新
    $('#vip-btn-refresh').on('click',function(){
        // 重载方式 1
        consume_table.reload();
        // 重载方式 2
        // table.reload('table-id-2', {})
    });
});
</script>
</body>
</html>