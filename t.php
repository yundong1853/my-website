<?php
/*!
@name:防洪接口文件
@description:防洪接口调用文件
@author:墨渊 
@version:4.6
@time:2020-03-15
@copyright:优启梦&墨渊
*/
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-Type: text/html; charset=utf-8");

include './includes/common.php';

if ($conf['jk_black'] != false) {
  $jk_black = trim($conf['jk_black'],'|');
  $blacks = explode('|',$jk_black);
  if (in_array($_SERVER['HTTP_HOST'],$blacks) == true) {
    include TEMPLATE.'tips/close.html';
    exit();
  }
}
if ($_SESSION['user_report_close'] == 'close') {
	include TEMPLATE.'tips/lahei.html';
	exit();
}
if (!empty($_SERVER["QUERY_STRING"]) && $conf['htacces'] == 0) {
	$url = htmlspecialchars(preg_replace('/^uid=(.*)$/i','$1',$_SERVER["QUERY_STRING"]));
	$position = strpos($url, '&');
	$url = $position === false ? $url : substr($url, 0, $position);
}else{
	$url = $_SERVER["REQUEST_URI"];
	$position = strpos($url, '?');
	$url = $position === false ? $url : substr($url, 0, $position);
}


// 删除前后的“/”
$url = trim($url, '/');
// 使用“/”分割字符串，并保存在数组中
$urlArray = explode('/', $url);

// 删除空的数组元素
$urlArray = array_filter($urlArray);
$extension = include (SYSTEM_ROOT.'/model/jump.format.php');
$extension[] = '.'.$conf['t_go_format'];
//$extension      = array('.php','.html','.aspx','.xhtml','.jsp','.xml','.css','.js','.jpg','.png','.apk','.exe','.dll','.dwg','.xls','.doc','.ppt','.pdf','.txt','.json','.ae','.~','.'.$conf['t_go_format']);
$uid            = str_replace($extension, '', end($urlArray));
$uid 			= trim($uid, '=');

$row = Auth::query($uid,'uid');
$s = QStasis($row);
if ($row['status'] == 3) {
	include TEMPLATE.'tips/null.html';
	exit();
}elseif ($row['status'] == 2) {
	include TEMPLATE.'tips/lahei.html';
	exit();
}elseif ($row['status'] == 1) {
	if ($conf['go_type'] == 2) {
		if ($date > $row['url_end_time']) {
			include TEMPLATE.'tips/daoqi.html';
			exit();
		}elseif ($row['url_nums'] <= 0) {
			include TEMPLATE.'tips/nonum.html';
			exit();
		}
	}
	if ($row['end_time'] && $date > $row['end_time']) {
		include TEMPLATE.'tips/daoqi.html';
		exit();
	}
}else{
	if ($conf['go_type'] == 1) {
		include TEMPLATE.'tips/nobai.html';
		exit();
	}elseif ($conf['go_type'] == 2) {
		if ($date > $row['url_end_time']) {
			include TEMPLATE.'tips/daoqi.html';
			exit();
		}elseif ($row['url_nums'] <= 0) {
			include TEMPLATE.'tips/nonum.html';
			exit();
		}
	}
	$s = getstasis();
}

$Erow = $DB->get_row('SELECT * from uomg_enter WHERE domain="'.$_SERVER['HTTP_HOST'].'" OR domain LIKE "*.'.getBaseDomain($_SERVER['HTTP_HOST']).'%";');

if (!empty($Erow)) {
	$resulturl = GetDomain($Erow['type'],false);
	if ($resulturl === 0) {
		sysmsg('防洪接口异常，联系QQ'.$conf['kfqq'].'!');
	}

	if ($conf['go_path'] == 0) {
		$resulturl .= $conf['go_file'];
	}elseif ($conf['go_path'] == 1) {
		$resulturl .= $conf['go_file'].'/';
	}elseif ($conf['go_path'] == 2) {
		$resulturl .= '/'.getRandChar2(6).'/';
	}

	if ($conf['htacces'] == 0) {
		$resulturl .= '?'.$uid;
	}elseif ($conf['htacces'] == 1) {
		$resulturl .= $uid;
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
	//header('location:http://'.GetDomain($Erow['type'],false).$conf['go_file'].'?'.$uid.'.'.$conf['t_go_format']);
	header('location:http://'.$resulturl);
}

if ($conf['go_type'] == 2)	$DB->query("update `uomg_report` set url_nums = url_nums-1 where uid='".$uid."';");

$t = file_get_contents(TEMPLATE.'stasis/'.$s);
include TEMPLATE.'/stasis.conf';
foreach ($stasis[$s] as $key => $val) {  
    $t = str_replace('{'.$key.'}',$val['values'],$t);
}

$rid=getRandChar2(6);
$ver=mt_rand(100000,999999).time().mt_rand(100000,999999);
$jsar = array(
	'uid'			=>	$uid
	,'rid'			=>	$rid
	,'longurl'		=>	$row['url']
	,'site_title'	=>	$row['title']
	,'cnzzid'		=>	$conf['cnzzid']
	,'bdtjid'		=>	$conf['bdtjid']
	,'qq_report'	=>	$conf['qq_report']
	,'vx_report'	=>	$conf['vx_report']
	,'f12_report'	=>	$conf['f12_report']
	,'t_go_url'		=>	$conf['t_go_url']
	,'delay'		=>	$conf['t_go_delay']
	,'vxurl'		=>	$conf['t_weixin_url']
	,'t_format'		=>	$conf['t_go_format']
	,'user_report'	=>	$conf['user_report']
	,'qq_report2'	=>	$conf['qq_report2']
);
foreach($jsar as $key => $value) {
    $js .= 'var '.$key.' = "'.$value.'";';
}
$html = str_replace(
	array('{name}','{ver}','{rid}','{longurl}','{js}')
	, array($conf['name'],$ver,$rid,$row['url'],$js)
	, $t
);
ob_end_clean();
switch ($conf['t_encode']) {
	case 0:
		echo $html;
		break;
	case 1:
		$html = escape($html);
		$html = str_replace('%',' ',$html);
		$change = getRandChar2(rand(8,20));
		echo '<script>function '.$change.'('.$rid.'){document.write((unescape('.$rid.')));};'.$change.'("'.$html.'".replace(/ /g,"%"));</script>';
		break;
	case 2:
		$html = urlencode($html);
		$html = str_replace('+',' ',$html);
		$change = getRandChar2(rand(8,20));
		echo '<script>function '.$change.'('.$rid.'){document.write((decodeURIComponent('.$rid.')));};'.$change.'("'.$html.'".replace(" ","+"));</script>';
		break;
	case 3:
		$html = base64_encode($html);
		$html = str_replace('+',' ',$html);
		$change = getRandChar2(rand(8,20));
		echo '<script>function '.$change.'('.$rid.'){document.write((window.atob('.$rid.')));};'.$change.'("'.$html.'".replace(/ /g,"+"));</script>';
		break;
	case 4:
		$html = urlencode($html);
		$html = str_replace('+',' ',$html);
		$html = json_encode($html,true);
		$change = getRandChar2(rand(8,20));
		echo '<script>function '.$change.'('.$rid.'){document.write(decodeURIComponent(JSON.parse('.$rid.')));};'.$change.'(\''.$html.'\');</script>';
		break;
	default:
		# code...
		break;
}

?>