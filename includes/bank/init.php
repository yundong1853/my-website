<?php
error_reporting(0);
session_start();
define('IN_CRONLITE', true);
define('IN_OTHER', true);
define('BANK_ROOT', dirname(__FILE__).'/');
define('SYSTEM_ROOT', dirname(BANK_ROOT).'/');

date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");

if (function_exists("set_time_limit")){
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort")){
	@ignore_user_abort(true);
}

$scriptpath=str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$sitepath.'/';
require (SYSTEM_ROOT.'../config.php');
require_once(SYSTEM_ROOT.'libs/function.base.php');

//连接数据库
$DB=new DB($dbconfig['host'],$dbconfig['user'],$dbconfig['pwd'],$dbconfig['dbname'],$dbconfig['port']);

//加载系统配置
$CACHE=new CACHE();
$conf=unserialize($CACHE->read());
if(empty($conf['version']))$conf=$CACHE->update();

require_once(SYSTEM_ROOT.'model/module.php');

$clientip=real_ip();

if(isset($_COOKIE["user_token"])){
	$token=authcode(daddslashes($_COOKIE['user_token']), 'DECODE', SYS_KEY);
	list($user, $sid) = explode("\t", $token);
	$udata = $DB->get_row("SELECT * FROM `uomg_user` WHERE username='$user' limit 1");
	$session=md5($udata['username'].$udata['password'].$password_hash.$_SERVER['HTTP_USER_AGENT']);
	$user_login=($session==$sid)?1:0;
}

function showalert($msg,$status,$orderid=null,$tid=0){
	if($tid==-1)$link = '../../../user/';
	elseif($tid==-2)$link = '../../../user/regok.php?orderid='.$orderid;
	else $link = '../../../user/?buyok=1';
	echo '<meta charset="utf-8"/><script>alert("'.$msg.'");window.location.href="'.$link.'";</script>';
}