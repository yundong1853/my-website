<?php
/**
 * 
**/
include("../includes/init.php");
$method=isset($_GET['method'])?$_GET['method']:null;
switch ($method) {
    case 'nav':
        $list = array(
            array('title'=>'仪表板','icon'=>'&#xe777;','href'=>'dashboard.php'),
            array('title'=>'价目表','icon'=>'&#xe65f;','href'=>'price.php'),
            array('title'=>'授权管理','icon'=>'&#xe660;','list'=>array(
                array('title'=>'添加授权','icon'=>'&#xe65c;','href'=>'auth-add.php'),
                array('title'=>'授权管理','icon'=>'&#xe6c7;','href'=>'auth.php'),
            )),
            array('title'=>'黑名单','icon'=>'&#xe6ff;','list'=>array(
                array('title'=>'域名黑名单','icon'=>'&#xe628;','href'=>'relieve-domain.php'),
                array('title'=>'ＩＰ黑名单','icon'=>'&#xe60a;','href'=>'relieve-ip.php'),
            )),
        );
        $arr = array('code'=>0,'msg'=>'获取成功','cfg'=>array('width'=>count($list)),'list'=>$list);
        showjson($arr);
        break;
    case 'count1':
        $arr = array(
            'code' => 0, 
            'msg' => '获取成功', 
            'list' => array(
                array('type'=>1,'title' =>'服务器接口数','count'=>$DB->count("SELECT count(*) FROM uomg_domain WHERE 1")),
                array('type'=>1,'title' =>'接口更新时间','count'=>$DB->get_row("SELECT date FROM uomg_domain WHERE 1 order by date DESC limit 1")['date']),
                array('type'=>2,'img' =>'https://q2.qlogo.cn/headimg_dl?spec=100&dst_uin='.$udata['qq'],'nickname'=>$udata['username']),
            ), 
        );
        showjson($arr);
        break;
    case 'count2':
        $arr = array(
            'code' => 0, 
            'msg' => '获取成功', 
            'list' => array(
                array('type'=>1,'title' =>'账户余额','count'=>$udata['money']),
                array('type'=>1,'title' =>'登录时间','count'=>$udata['login_time']),
                array('type'=>2,'title' =>'登录ＩＰ','count'=>$udata['login_ip']),
            ), 
        );
        showjson($arr);
        break;
    case 'notice':
        $rs = $DB->query('select * from `uomg_notice` order by id desc limit 0,6');
        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','list' => $arr));
        break;
    case 'notice-panel':
        $rel = $DB->get_row('select * from `uomg_notice` where id='.getParam('id').' limit 1');
        showjson(array('code' => 0,'msg' => '获取成功！','data' => $rel));
        break;
    case 'bill':
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql=' uid='.$udata['id'];

        $count =$DB->count('select count(*) from `uomg_bill` where '.$sql);
        $rs = $DB->query('select * from `uomg_bill` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'auth':
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql='status!=2 and uid='.$udata['id'];

        $count =$DB->count('select count(*) from `uomg_auth` where '.$sql);
        $rs = $DB->query('select * from `uomg_auth` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $res['ios_qq']=getTempname($res['ios_qq']);
            $res['an_qq']=getTempname($res['an_qq']);
            $res['ios_vx']=getTempname($res['ios_vx']);
            $res['an_vx']=getTempname($res['an_vx']);
            $res['other']=getTempname($res['other']);
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'report':
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql='aid='.$udata['id'];

        $count =$DB->count('select count(*) from `uomg_report` where '.$sql);
        $rs = $DB->query('select * from `uomg_report` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $res['ios_qq']=getTempname($res['ios_qq']);
            $res['an_qq']=getTempname($res['an_qq']);
            $res['ios_vx']=getTempname($res['ios_vx']);
            $res['an_vx']=getTempname($res['an_vx']);
            $res['other']=getTempname($res['other']);
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'relieve_i':
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql='type=2 ';

        $count =$DB->count('select count(*) from `uomg_iptable` where '.$sql);
        $rs = $DB->query('select * from `uomg_iptable` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $arr[] = $res;
        }
        showjson(array('code' => 0,'msg' => '获取成功！','count' => $count,'data' => $arr));
        break;
    case 'relieve_d':
        $limit=intval(getParam('limit'));
        $page= intval(getParam('page')-1);
        $sql='status=2 ';

        $count =$DB->count('select count(*) from `uomg_auth` where '.$sql);
        $rs = $DB->query('select * from `uomg_auth` where '.$sql.' order by id desc limit '.$page.','.$limit);
        $arr = array();
        while($res = $DB->fetch($rs)){
            $res['ios_qq']=getTempname($res['ios_qq']);
            $res['an_qq']=getTempname($res['an_qq']);
            $res['ios_vx']=getTempname($res['ios_vx']);
            $res['an_vx']=getTempname($res['an_vx']);
            $res['other']=getTempname($res['other']);
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