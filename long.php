<?php
/*!
@name:防洪还原文件
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


$url = (isset($_GET['url']))?$_GET['url']:$_POST['url'];
if(!isset($url))  exit('{"msg":"URL不能为空"}');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0); // 302 redirect
curl_exec($ch);
$Headers = curl_getinfo($ch);
curl_close($ch);

$urlArray = empty($Headers['redirect_url'])?parse_url($Headers['url']):parse_url($Headers['redirect_url']);

$uid 	 = empty($urlArray['query'])?$urlArray['path']:$urlArray['query'];

// 删除前后的“/”
$uid = trim($uid, '/');
// 使用“/”分割字符串，并保存在数组中
$urlArray = explode('/', $uid);

// 删除空的数组元素
$urlArray = array_filter($urlArray);
$extension = include (SYSTEM_ROOT.'/model/jump.format.php');
$extension[] = '.'.$conf['t_go_format'];
$uid            = str_replace($extension, '', end($urlArray));
$uid 			= trim($uid, '=');

$row = $DB->get_row("select * from `uomg_report` where uid='".$uid."';");
$result=array(
	'code'=>1,
	'ae_url'=>$row['url']
);
print_r(json_encode($result));

unset($url,$url_arr,$ch,$row,$result,$ch);