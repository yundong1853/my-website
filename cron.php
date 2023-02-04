<?php
/*!
@name:检查域名状态
@description:检查域名报毒状态
@author:墨渊 
@version:4.6
@time:2020-03-15
@copyright:优启梦&墨渊
*/
include './includes/common.php';

if ($_GET['key'] == $conf['cronkey']) {
	if ($_GET['event'] === 'update') {
		$msg = '报毒域名清单<br/>';
		foreach ($_POST as $res) {
			if ($res['status'] == 2) {
				$msg .= $res['desc'].'：'.$res['domain'].'<br/>';
				$DB->query('update `uomg_'.$_GET['table'].'` set `status` = 2, `desc` = "'.$res['desc'].'" where id="'.$res['id'].'";');
			}
		}
		if ($conf['mail_tips'] == 1) send_mail($mail['addressee'],'UomgJump',$msg);
		echo '已更新报毒域名';
	}elseif ($_GET['event'] === 'mail') {
		$msg = base64_decode($_POST['msg']);
		send_mail($mail['addressee'],'UomgJump',urldecode($msg));
		echo '已发送邮件提醒';
	}else{
		//查域名状态
		$query = array(
			 'key'		=>	$conf['cronkey']
			,'token'	=>	$conf['checkkey']
			,'callback'	=>	$conf['siteurl'].'/cron.php?event=update'
		);

		if ($conf['mail_tips'] == 2) $query['mail']	= $mail['addressee'];
		$query['table']	= 'domain';
		OutputDomain($query);
		$query['table']	= 'enter';
		OutputDomain($query);
		echo json_encode(array('code'=>0,'msg'=>'已提交检测任务！','result'=>200),320);
	}
}else{
	$result=array('code'=>0,'msg'=>'监控码错误！','result'=>10008);
	echo json_encode($result,320);
}
$DB->close();

//删除无用变量
unset($_POST,$_GET,$msg,$query,$page_size,$list_num,$page_count);
function OutputDomain($query){
	global $DB,$conf;
	$page_size = 10;
	$page_count = ceil($DB->count('select count(*) from `uomg_'.$query['table'].'` where status = 1') / $page_size);
	for ($i = 1; $i <= $page_count; $i++) { 
		$start_num = ($i - 1) * $page_size;
		$rs = $DB->query('select * from `uomg_'.$query['table'].'` where status = 1  LIMIT '.$start_num.', '.$page_size);
		$ds = array();
		while($res = $DB->fetch($rs)){
			$domain = 'http://';
			$domain .= str_replace('*.', getRandChar(3).'.', $res['domain']);
			if ($conf['go_path'] == 0) {
				$domain .= $conf['go_file'].'/';
			}elseif ($conf['go_path'] == 1) {
				$domain .= $conf['go_file'].'/';
			}elseif ($conf['go_path'] == 2) {
				$domain .= '/'.getRandChar2(6).'/';
			}
			$res['domain'] = $domain;
			$ds['data'][] = $res;
		}
		InspectDomain($query,$ds);

	}
}
function InspectDomain($query,$post){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'http://check.uomg.com/api/urlsec/list_ckeck?'.http_build_query($query));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); 
	curl_setopt($ch, CURLOPT_USERAGENT, 'www.uomg.com');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	echo curl_exec($ch);
	curl_close($ch);
}