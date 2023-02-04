<?php
/**
 * 
**/
include("../includes/init.php");
$method=isset($_GET['method'])?$_GET['method']:null;
switch ($method) {
    case 'detail-data':
        $startDate = getParam('startDate',date("Y-m-d",strtotime("-7 day")));
        $endDate = getParam('endDate',date("Y-m-d"));
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql=' uid="'.getParam('uid').'" and click_time >= "'.$startDate.'" and click_time <= "'.$endDate.'"';

        $count =$DB->count('select count(DISTINCT longurl,click_time) from `uomg_log` where '.$sql);
        $rs = $DB->query('select count(1) as pv ,count(DISTINCT ip_address) as ip ,count(DISTINCT user_agent,ip_address) as uv ,click_time as date ,longurl from `uomg_log` where '.$sql.' group by click_time,longurl order by click_time desc limit '.$page.','.$limit);

        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'detail-visitor':
        $startTime = strtotime(getParam('startDate'). '00:00:00');
        $endTime = strtotime(getParam('endDate'). '23:59:59') + 1;
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql=' uid="'.getParam('uid').'" and time >= '.$startTime.' and time <= '.$endTime;

        $count = $DB->count('select count(*) from `uomg_log` where '.$sql);
        $rs = $DB->query('select * from `uomg_log` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $res['time'] = date('Y-m-d h:i:s',$res['time']);
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'compare':
        $queryDate = getParam('queryDate',date("Y-m-d"));
        $startTime = strtotime($queryDate. '00:00:00');
        $endTime = strtotime($queryDate. '23:59:59') + 1;
        $where =' uid="'.getParam('uid').'" and time >= '.$startTime.' and time <= '.$endTime;

        $rel = $DB->get_row('select count(1) as PV ,count( DISTINCT ip_address) as IP,count( DISTINCT user_agent,ip_address) as UV from `uomg_log` where'.$where);
        $list = array();
        $list[] = array(
            'title'     =>  '浏览量（PV）'
            ,'count'    =>  (int)$rel['PV']
        );
        $list[] = array(
            'title'     =>  '独立访客数（UV）'
            ,'count'    =>  (int)$rel['UV']
        );
        $list[] = array(
            'title'     =>  '访问量（IP）'
            ,'count'    =>  (int)$rel['IP']
        );
        showjson(array('code' => 0,'msg' => '获取成功！','list' => $list));
        break;
    case 'compareHours':
        $queryDate = getParam('queryDate',date("Y-m-d"));
        $startTime = strtotime($queryDate. '00:00:00');
        $endTime = strtotime($queryDate. '23:59:59') + 1;
        $arr = array();

        $rel = $DB->query('select count(1) as pv ,count( DISTINCT ip_address) as ip,count( DISTINCT user_agent,ip_address) as uv from `uomg_log` where'.$where);


        for ($i=1; $i < 25; $i++) {
            $Time1 = $startTime + (($i -1) * 3600);
            $Time2 = $startTime + ($i * 3600);
            $sql .= 'select count(1) as pv ,count( DISTINCT ip_address) as ip,count( DISTINCT user_agent,ip_address) as uv from `uomg_log` where uid="'.getParam('uid').'" and time >= '.$Time1.' and time <= '.$Time2.PHP_EOL;
            if ($i != 24) {
                $sql .= 'union all '.PHP_EOL;
            }
        }
        $rs = $DB->query($sql);
        while($res = $DB->fetch($rs)){
            $arr['uv'][] = $res['uv'];
            $arr['pv'][] = $res['pv'];
            $arr['ip'][] = $res['ip'];
        }

        showjson(array('code' => 0,'msg' => '获取成功！','data' => $arr));
        break;
    case 'compareMap':
        $queryDate = getParam('queryDate',date("Y-m-d"));
        $startTime = strtotime($queryDate. '00:00:00');
        $endTime = strtotime($queryDate. '23:59:59') + 1;
        $where =' uid="'.getParam('uid').'" and time >= '.$startTime.' and time <= '.$endTime;


        $rs = $DB->query('select cityName name,count(1) value from `uomg_log` where'.$where.' group by cityName;');
        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','data' => $arr));
        break;
    case 'compareCity':
        $queryDate = getParam('queryDate',date("Y-m-d"));
        $startTime = strtotime($queryDate. '00:00:00');
        $endTime = strtotime($queryDate. '23:59:59') + 1;
        $where =' uid="'.getParam('uid').'" and time >= '.$startTime.' and time <= '.$endTime;
        $rs = $DB->query('select cityName,count(1) as PV,count( DISTINCT user_agent,ip_address) as UV,count( DISTINCT ip_address) as IP from `uomg_log` where'.$where.' group by cityName order by PV desc limit 10;');
        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr['city'][] = $res['cityName'];
            $arr['PV'][] = $res['PV'];
            $arr['UV'][] = $res['UV'];
            $arr['IP'][] = $res['IP'];
        }

        $arr['city'] = array_reverse($arr['city']);
        $arr['PV'] = array_reverse($arr['PV']);
        $arr['UV'] = array_reverse($arr['UV']);
        $arr['IP'] = array_reverse($arr['IP']);
        showjson(array('code' => 0,'msg' => '获取成功！','data' => $arr));
        break;
    case 'compareReferer':
        $queryDate = getParam('queryDate',date("Y-m-d"));
        $startTime = strtotime($queryDate. '00:00:00');
        $endTime = strtotime($queryDate. '23:59:59') + 1;
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $where =' uid="'.getParam('uid').'" and time >= '.$startTime.' and time <= '.$endTime;

        $count =$DB->count('select count(*) from `uomg_log` where '.$where);
        $rs = $DB->query('select referer,count(1) as nums from `uomg_log` where '.$where.' group by referer order by nums desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $res['pv'] = $DB->count('select count(*) from uomg_log where referer="'.$res['referer'].'" and '.$where);
            $res['uv'] = $DB->count('select count(DISTINCT user_agent,ip_address) from uomg_log where referer="'.$res['referer'].'" and '.$where);
            $res['ip'] = $DB->count('select count(DISTINCT ip_address) from uomg_log where referer="'.$res['referer'].'" and '.$where);
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    default:
        showjson(array('res' => 5,'msg' => '参数错误！'));
        break;
}
function showjson($arr){
    //ob_end_clean();
    exit(json_encode($arr));
}