<?php
/*!
@name:屏蔽检测系统
@description:反腾讯网址安全检测系统
@author:墨渊
@time:2019-07-15
@copyright:优启梦&墨渊
*/
if (TXPROTECT == false) return;
$tx_ua = file_get_contents(__DIR__.'/txspdier.ua');
foreach(explode(PHP_EOL,$tx_ua) as $UaRes){
	if(strtolower($_SERVER['HTTP_USER_AGENT']) == strtolower($UaRes)){
		TxSpdier('欢迎使用！');
	}
}

$tx_ipdb = file_get_contents(__DIR__.'/txspdier.db');

/*$ip = '61.151.178.197';
$RemoteIp=bindec(decbin(ip2long($ip)));*/
$RemoteIp=bindec(decbin(ip2long(real_ip())));

foreach(explode(PHP_EOL,$tx_ipdb) as $IpRes){
	if($RemoteIp == bindec(decbin(ip2long($IpRes))) && !is_array($IpRes))	TxSpdier('欢迎使用！');
	$iprange	=	explode('-',$IpRes);
	if (is_array($iprange)) {
		if($RemoteIp >= bindec(decbin(ip2long($iprange[0]))) && $RemoteIp <= bindec(decbin(ip2long($iprange[1]))))	TxSpdier('欢迎使用！');
	}
	
}

function TxSpdier($val = 0)
{
	include (__DIR__.'/../../template/tips/txprotect.html');
	exit();
}