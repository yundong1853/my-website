<?php
/**
 * API接口
**/
define('TXPROTECT', false);
include("./includes/common.php");
$method=isset($_GET['method'])?$_GET['method']:null;
switch ($method) {
    case 'joinqun':
        $qun=isset($_GET['qun'])?$_GET['qun']:'295023774';
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(strpos($ua, 'windows') ){
            $data=get_curl('https://shang.qq.com/wpa/g_wpa_get?guin='.$qun.'&t='.time(),0,'http://qun.qq.com/join.html');
            $arr=json_decode($data,true);
            $idkey=$arr['result']['data'][0]['key'];
            header('Location:http://shang.qq.com/wpa/qunwpa?idkey='.$idkey);
        }else{
            header('Location:mqqapi://card/show_pslcard?src_type=internal&version=1&card_type=group&source=qrcode&uin='.$qun);
        }
        unset($qun,$data,$arr,$idkey);
        exit();
        break;
    case 'get.title':
        $url = (isset($_GET['url']))?$_GET['url']:$_POST['url'];
        if(!isset($url))  exit(json_encode(array('code'=>201601,'msg'=>'URL不能为空'),320));
        $row = $DB->get_row("select * from `uomg_report` where `url` like '%".$url."%';");
        if ($row['title']) {
            exit(json_encode(array('code'=>1,'title'=>$row['title']),320));
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Baiduspider-render/2.0; +http://www.baidu.com/search/spider.html)");
        $ret=curl_exec($ch);
        curl_close($ch);
        preg_match('/<title>(.*)<\/title>/i',$ret,$title);
        $title = str_replace(array("\r\n", "\r", "\n", ',', ' '), '', $title[1]); 
        if(!isset($title))  exit(json_encode(array('code'=>201602,'msg'=>'获取失败，请重试！'),320));
        
        $DB->query("update `uomg_report` SET title='".$title."' where id='".$row['id']."';");

        $result=array(
            'code'=>1,
            'title'=>$title
        );
        print_r(json_encode($result),320);

        unset($url,$ch,$result,$ret,$title);
        exit();
        break;
    case 'qqtalk':
        $qq = isset($_GET['qq'])?$_GET['qq']:'774740085';
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        if(strpos($ua, 'android') != false || strpos($ua, 'ipad') != false || strpos($ua, 'iphone') != false ){
            $url = 'mqqwpa://im/chat?chat_type=wpa&version=1&src_type=web&web_src=oicqzone.com&uin='.$qq;
            header('Location:'.$url);
       /* }elseif (strpos($ua, 'windows') != false) {
            header('Location:tencent://Message/?uin='.$qq);*/
        }else{
            header('Location:http://wpa.qq.com/msgrd?v=3&site=qq&menu=yes&uin='.$qq);
        }
        unset($_POST,$_GET,$qq,$ua);
        exit();
        break;
    case 'index_num':
        $urls = $DB->count("SELECT count(*) FROM uomg_domain WHERE 1"); //获取域名数量
        $sum = $DB->get_row("SELECT SUM(count) FROM uomg_report");//获取生成数量
        $logs = $DB->count("SELECT count(*) FROM uomg_log");//获取访问数量

        $json=array(
            'UrlUseNum' => $urls,
            'LogUseNum' => $logs,
            'ApiUseNum' => $sum['SUM(count)']
        );
        unset($urls,$sum,$logs,$logs);
        exit(json_encode($json,320));
        break;
    case 'tj':
        if ($conf['visitorlog'] == 0) exit('401');
        if ($_GET['uid'] == 'undefined') exit('401');
        if (empty($_GET['ua'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }else{
            $user_agent = $_GET['ua'];
        }
        $row = $DB->get_row("select * from `uomg_report` where uid='".$_GET['uid']."';");
        if (empty($row['url'])) exit('401');

        $remoteip = real_ip();
        $ua = new UserAgent($user_agent);
        $city = (new City)->find($remoteip, 'CN');
        if (empty($ua->mobile)) $ua->mobile = '电脑设备';

        $param = array(
            'uid'       =>  $_GET['uid']
            ,'longurl'  =>  $row['url']
            ,'time'     =>  time()
            ,'cityName'         =>  $city[1]
            ,'provinceName'     =>  $city[2]
            ,'browser'          =>  $ua->browser
            ,'browserVersion'   =>  $ua->version
            ,'system'           =>  $ua->platform
            ,'brand'            =>  $ua->mobile
            ,'domain'           =>  getBaseDomain($row['url'],'host')
            ,'click_time'       =>  date("Y-m-d")
            ,'user_agent'       =>  $user_agent
            ,'ip_address'       =>  $remoteip
        );

        $DB->insert_array('uomg_log',$param);
        /*$where = 'where uid="'.$_GET['uid'].'" and time >= unix_timestamp(date_sub(curdate(),interval 0 day)) and time <= unix_timestamp(date_sub(curdate(),interval -1 day))';
        $DB->query('REPLACE INTO uomg_log_data (umd5,uid,longurl,date,pv,ip,uv)  
            select 
                md5(concat("'.$_GET['uid'].'",current_date())) as umd5
                , uid
                , longurl
                , current_date() as date
                , count(1) as pv
                , count( DISTINCT user_agent,ip_address) as uv
                , count( DISTINCT ip_address) as ip 
            from `uomg_log` '.$where
        );*/
        exit(date("Y-m-d"));
        break;
    case 'background':
        $url='http://www.lofter.com/dwr/call/plaincall/ActMiscBean.getLatestIndexImages.dwr';
        $post='callCount=1&scriptSessionId=${scriptSessionId}187&c0-scriptName=ActMiscBean&c0-methodName=getLatestIndexImages&c0-id=0&c0-param0=number:100&batchId=126886&';
        $referer='http://www.lofter.com/login?urschecked=true';
        $rel= get_curl($url,$post,$referer);
        preg_match_all('/imageUrl=[\"\"](.*?)[\"\"]/i',$rel,$matches);
        $key=array_rand($matches[1],1);
        $rel=str_replace(array('http://','imgcdn.ph'), array('https://','img0.ph'), $matches[1][$key]);
        if ($rel) {
            $json = array('code' => 1,'msg' => $rel);
        }else{
            $json = array('code' => 5,'msg' => '获取失败！');
        }
        unset($url,$post,$referer,$rel,$key,$method);
        exit(json_encode($json,320));
        break;
    case 'append':
        if ($_POST['key'] != $conf['apikey']) exit('{"code":10008,"msg":"URL不能为空"}');
        $i=0;
        $type=addslashes($_POST['item']);
        $text=trim(strtolower($_POST['text']));
        if($text==NULL or $type==NULL){
            $json = array('{"code":5,"msg":"请确保每项都不为空！"}');
        } else {
            $val = str_replace('http://', '', $text);
            $val = str_replace('https://', '', $val);
            $val = str_replace("\n\r", '', $text);
            if ($type == 'domain') {
                $myrow=$DB->get_row("select * from `uomg_domain` where domain='$val' limit 1");
                if (!$myrow){
                    $sql = $DB->get_row("insert into `uomg_domain` (`domain`,`date`,`status`) values ('".$val."','".$date."','1')");
                    $i++;
                }
            }elseif ($type == 'u_black') {
                if (!$DB->get_row("select * from `uomg_auth` where domain='".$val."';")) {
                    $DB->query("insert into `uomg_auth` (uid,domain,ios_qq,an_qq,ios_vx,an_vx,other,add_time,end_time,status) value 
                    ('-1','".$val."','".$conf['stasis_iosqq']."','".$conf['stasis_anqq']."','".$conf['stasis_ioswx']."','".$conf['stasis_anwx']."','".$conf['stasis_other']."','".$date."',DATE_ADD('".$date."', INTERVAL '30' DAY ),2);");
                    $i++;
                }
            }elseif ($type == 'u_white') {
                if (!$DB->get_row("select * from `uomg_auth` where domain='".$val."';")) {
                    $DB->query("insert into `uomg_auth` (uid,domain,ios_qq,an_qq,ios_vx,an_vx,other,add_time,end_time,status) value 
                    ('-1','".$val."','".$conf['stasis_iosqq']."','".$conf['stasis_anqq']."','".$conf['stasis_ioswx']."','".$conf['stasis_anwx']."','".$conf['stasis_other']."','".$date."',DATE_ADD('".$date."', INTERVAL '30' DAY ),1);");
                    $i++;
                }
            }elseif ($type == 'i_black') {
                $myrow=$DB->get_row("select * from `uomg_iptable` where ip='$val' limit 1");
                if (!$myrow) {
                    $DB->query("insert into `uomg_iptable` (`ip`,`date`,`type`) values ('".$val."','".$date."','2')");
                    $i++;
                }
            }elseif ($type == 'i_white') {
                $myrow=$DB->get_row("select * from `uomg_iptable` where ip='$val' limit 1");
                if (!$myrow) {
                    $DB->query("insert into `uomg_iptable` (`ip`,`date`,`type`) values ('".$val."','".$date."','1')");
                    $i++;
                }
            }
            $json = array('code' => 1,'msg' => '成功添加'.$i.'条记录！');
        }
        unset($i,$type,$text,$_POST,$val,$myrow);
        exit(json_encode($json,320));
        break;
    case 'ip_update':
        if ($_POST['key'] != $conf['apikey']) exit('{"code":10008,"msg":"URL不能为空"}');
        $newip=addslashes($_POST['newip']);
        $oldip=addslashes($_POST['oldip']);
        $DB->query("update `uomg_domain` SET domain='".$newip."',date='".$date."',status=1 where domain='".$oldip."';");
        $myrow = $DB->get_row("select * from `uomg_domain` where domain='".$newip."' limit 1");
        if ($myrow) exit('{"code":1,"msg":"成功更新记录！"}');
        else exit('{"code":0,"msg":"更新记录失败！"}');
        break;
    case 'uploads':
        if (class_exists('CURLFile')) { // php 5.5
            $post['file'] = new \CURLFile(realpath($_FILES['imageData']['tmp_name']));
        } else {
            $post['file'] = '@'.realpath($_FILES['imageData']['tmp_name']);
        }
        $rel = get_curl('https://search.jd.com/image?op=upload',$post);
        preg_match('/callback(?:\(\")(.*)(?:\"\))/i',$rel,$matches);
        if (!$matches[1]) {
            $arr = array('code'=>0,'msg'=>'图片上传失败！');
        }else{
            $arr = array(
                'code'  =>  200,
                'imgurl'=>  'https://img'.rand(10,14).'.360buyimg.com/uba/'.$matches[1]
            );
        }
        exit(json_encode($arr,320));
        break;
    case 'qrlogin':
        $post = array(
            'base64image'   =>  $_POST['image'],
            'submittype'    =>  'drag',
            'src'           =>  'st'
        );
        $qrcode = soimg_decode(soimg_upload($post));
        $url = 'http://grouproam.qq.com/cgi-bin/httpconn?';
        $url .= http_build_query(array(
            'htcmd' =>  '0x6ff0080'
            ,'u'    =>  $qrcode
        ));
        $json = array('code' => 200,'url' => $url);
        exit(json_encode($json,320));
        break;
    case 'vx_report':
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        header("Content-Type: text/html; charset=utf-8");
        echo '<script src="/assets/plugin/jump/vxtool.js"></script>';
    case 'm_report':
        include TEMPLATE.'tips/m_report.html';
        exit();
    case 'm_report_success':
        if ($conf['user_report_close'] == 1) {
            setcookie("user_report_close",'close');
            $_SESSION['user_report_close'] = 'close';
        }
        include TEMPLATE.'tips/m_report_success.html';
        exit();
    case 'report':
        if ($conf['mail_tips'] == 1) {
            send_mail(
                $mail['addressee']
                ,$conf['name'].'违规举报'
                ,'站点：'.$conf['siteurl'].'<br/>链接：'.getParam('url').'<br/>已被用户举报为'.getParam('reason').'，请核查！'.'<br/>用户IP：'.real_ip()
            );
            $arr = array('code'=>1,'msg'=>'已提交举报信息');
        }else{
            $arr = array('code'=>0,'msg'=>'已关闭该功能！');
        }
        exit(json_encode($arr,320));
        break;
    default:
        $json = array('code' => 5,'msg' => '参数错误！');
        exit(json_encode($json,320));
        break;
}
function soimg_decode($array){
    if (!$array) return false;
    $location = parse_url($array);
    if (!$location['query']) return false;
    parse_str($location['query'],$query);
    if (!$query['imgkey']) return false;
    return 'http://p0.so.qhimgs1.com/'.$query['imgkey'];
}
function soimg_upload($post=0){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://st.so.com/stu');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $httpheader[] = "Accept:application/json";
    $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
    $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
    $httpheader[] = "Connection:close";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($ch, CURLOPT_REFERER, 'http://st.so.com/');
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($ch);
    $Headers = curl_getinfo($ch);
    curl_close($ch);
    if ($data != $Headers){      
        return $Headers["url"];       
    }else{
        return false;
    }
}