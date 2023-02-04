<?php 
    include('../includes/init.php');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>数据概况</title>
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
                <div class="layui-card-header">流量概况</div>
                <div class="layui-card-body">
                    <div class="layui-row vip-btn-tool-box">
                        <div class="layui-btn-group">
                            <button type="button" class="layui-btn layui-btn-primary" id="today">今日</button>
                            <button type="button" class="layui-btn layui-btn-primary" id="yesterday">昨天</button>
                        </div>
                        <div class="layui-inline">
                            <input type="text" class="layui-input" id="queryday">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15 vip-dashboard-count-2"></div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">流量趋势</div>
                <div class="layui-card-body">
                    <div class="vip-demo-box" id="consume_chart"></div>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">地图数据</div>
                <div class="layui-card-body">
                    <div class="vip-demo-300-height-box" id="map_chart"></div>
                </div>
            </div>
        </div>
        <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">地区统计</div>
                <div class="layui-card-body">
                    <div class="vip-demo-300-height-box" id="city_chart"></div>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/html" id="count2Tpl">
    {{# layui.each(d.list, function(index, item){ }}
    {{# if(index>5){return ;} }}
    <div class="layui-col-md4 layui-col-sm4 layui-col-xs12">
        <div class="vip-chart bg-light">
            <div class="vip-chart-content">
                <h5 class="layui-elip">{{ item.title }}</h5>
                <h2 class="layui-elip">{{ item.count }}</h2>
            </div>
        </div>
    </div>
    {{# }) }}
</script>
<script type="text/javascript" src="../assets/user/layui/layui.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/echarts.min.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/echarts-china.js"></script>
<script type="text/javascript" src="../assets/user/js/echarts/theme/style.js"></script>
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
        queryData(top.location.uid, fun_date(0));
    });
    $('#yesterday').on('click',function(){
        $('#today').attr('class',"layui-btn layui-btn-primary");
        $('#yesterday').attr('class',"layui-btn layui-btn-normal");
        queryData(top.location.uid, fun_date(-1));
    });


    function queryData(uid,date) {
        app.ajaxTpl({
            url: './json.php?'
            ,where: {
                method: 'compare'
                , uid: uid
                , queryDate: date
            }
            ,tpl: '#count2Tpl'
            ,el: '.vip-dashboard-count-2'
            ,done: function(){
                //console.log('count.2 is done');
            }
        });
        $.getJSON('./json.php?', {
            method: 'compareHours'
            , uid: uid
            , queryDate: date
        }, function(json, textStatus) {
            var chart = echarts.init(document.getElementById('consume_chart'),'style');
            optionHours = {
                title: {text: '流量趋势'}
                ,tooltip : {
                    trigger: 'axis',
                    axisPointer: {type: 'cross',label: {backgroundColor: '#6a7985'}}
                }
                ,legend: {data:['IP','PV','UV']}
                ,toolbox: {feature: {saveAsImage: {}}}
                ,grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                }
                ,xAxis : {
                    type : 'category',
                    boundaryGap : false,
                    data : ["00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23"]
                }
                ,yAxis: {type: 'value'}
                ,series : [
                    {
                        name:'IP',
                        type:'line',
                        data:json.data.ip
                    },
                    {
                        name:'PV',
                        type:'line',
                        data:json.data.pv
                    },
                    {
                        name:'UV',
                        type:'line',
                        data:json.data.uv
                    },
                ]
            };
            chart.setOption(optionHours);
        });
        $.getJSON('./json.php?', {
            method: 'compareMap'
            , uid: uid
            , queryDate: date
        }, function(json, textStatus) {
            var optionMap = {  
                backgroundColor: '#FFFFFF',  
                title: {  
                    text: '全国地图大数据',  
                    subtext: '',  
                    x:'center'  
                }
                ,tooltip : {  
                    trigger: 'item'  
                }
                ,visualMap: {
                    min: 0,
                    max: 200,
                    left: 'left',
                    top: '50',
                    text: ['高', '低'],
                    calculable: true,
                    colorLightness: [0.2, 100],
                    color: ['#c05050','#e5cf0d','#5ab1ef'],
                    dimension: 0
                }
                ,series: [{  
                    name: '数据',  
                    type: 'map',  
                    mapType: 'china',   
                    roam: true,  
                    label: {  
                        //省份名称  
                        normal: {show: false},  
                        emphasis: {show: false}  
                    },  
                    data: json.data
                }]  
            };  
            //初始化echarts实例
            var chart = echarts.init(document.getElementById('map_chart'));
            //使用制定的配置项和数据显示图表
            chart.setOption(optionMap);
        });
        $.getJSON('./json.php?', {
            method: 'compareCity'
            , uid: uid
            , queryDate: date
        }, function(json, textStatus) {
            var optionCity = {
                title: {
                    text: '地区访问统计',
                    subtext: '前10名地区'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    data: ['UV','IP','PV']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'value',
                    boundaryGap: [0, 0.01]
                },
                yAxis: {
                    type: 'category',
                    data: json.data.city
                },
                series: [
                    {
                        name: 'UV',
                        type: 'bar',
                        stack: '总量',
                        data: json.data.UV
                    },
                    {
                        name: 'IP',
                        type: 'bar',
                        stack: '总量',
                        data: json.data.IP
                    },
                    {
                        name: 'PV',
                        type: 'bar',
                        stack: '总量',
                        data: json.data.PV
                    },
                ]
            };
            //初始化echarts实例
            var chart = echarts.init(document.getElementById('city_chart'));
            //使用制定的配置项和数据显示图表
            chart.setOption(optionCity);
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