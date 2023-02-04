<?php
/**
 * 拓展函数库
 * @copyright (c) UomgJump All Rights Reserved
 * @time 2018-08-12
 */
function send_mail($to, $sub, $msg) {
    global $mail,$conf;
    $From = $mail['name'];
    $Host = $mail['smtp'];
    $Port = $mail['port'];
    $SMTPAuth = 1;
    $Username = $mail['name'];
    $Password = $mail['pass'];
    $Nickname = $conf['name'];
    $SSL = ($Port == 25)?false:true;
    $send = new SMTP($Host , $Port , $SMTPAuth , $Username , $Password , $SSL);
    $send->att = array();
    if($send->send($to , $From , $sub , $msg, $Nickname)) {
      return 1;
    } else {
      return $send->log;
    }
}
function getSetting($k, $force = false){
	global $DB,$CACHE;
	if($force) return $setting[$k] = $DB->get_row("SELECT v FROM " . DBQZ . "_config WHERE k='$k' limit 1");
	$cache = $CACHE->get($k);
	return $cache[$k];
}
function saveSetting($k, $v){
	global $DB;
	$v = daddslashes($v);
	return $DB->query("REPLACE INTO uomg_config SET v='$v',k='$k'");
}
function showHomePagelist(){
  $handle = @opendir(TEMPLATE.'home/') OR die('uomgjump template path error!');
  $tpls = array('nums' => 0);
  while ($file = @readdir($handle)) {
    if( "." != $file && ".." != $file ) {
      $tplData = implode('', @file(TEMPLATE.'home/'.$file));
      preg_match("/Template Name:([^\r\n]+)/i", $tplData, $name);
      preg_match("/Description:([^\r\n]+)/i", $tplData, $desc);
      preg_match("/Version:([^\r\n]+)/i", $tplData, $version);
      preg_match("/Author:([^\r\n]+)/i", $tplData, $author);
      preg_match("/Author Url:([^\r\n]+)/i", $tplData, $authorurl);
      preg_match("/Preview Url:([^\r\n]+)/i", $tplData, $previewurl);
      $tpls[$file] = array(
      	'file' => $file
      	,'name' => $name[1]
      	,'desc' => $desc[1]
      	,'ver' => $version[1]
      	,'author' => $author[1]
      	,'author_url' => $authorurl[1]
      	,'img' => $previewurl[1]
      	 );
    }
  }
  closedir($handle);
  $tpls['nums'] = count($tpls);
  return $tpls;
}
function showStasisPagelist(){
  $handle = @opendir(TEMPLATE.'stasis/') OR die('uomgjump template path error!');
  $tpls = array();
  while ($file = @readdir($handle)) {
    if( "." != $file && ".." != $file ) {
      $tplData = implode('', @file(TEMPLATE.'stasis/'.$file));
      preg_match("/Template Name:([^\r\n]+)/i", $tplData, $name);
      preg_match("/Description:([^\r\n]+)/i", $tplData, $desc);
      preg_match("/Version:([^\r\n]+)/i", $tplData, $version);
      preg_match("/Author:([^\r\n]+)/i", $tplData, $author);
      preg_match("/Author Url:([^\r\n]+)/i", $tplData, $authorurl);
      preg_match("/Preview Url:([^\r\n]+)/i", $tplData, $previewurl);
      $tpls[$file] = array(
      	'file' => $file
      	,'name' => $name[1]
      	,'desc' => $desc[1]
      	,'ver' => $version[1]
      	,'author' => $author[1]
      	,'author_url' => $authorurl[1]
      	,'img' => $previewurl[1]
      	 );
    }
  }
  closedir($handle);
  $tpls['nums'] = count($tpls);
  return $tpls;
}
function Array2query($Array,$i1='=',$i2='&'){
    $Return='';
    foreach ($Array as $Key => $Value) {
        $Return.= $Key.$i1.urlencode($Value).$i2;
    }
    return $Return;
}
function checkurl(){
	global $DB;
	$sql = 'SELECT * from uomg_domain WHERE status=1';
	$urlstr = $DB->query($sql);
	while($res = $DB->fetch($urlstr)){
		$num[] = $res['domain'];
	}
	return $num[array_rand($num,1)];
}
function GetDomainNum($type = 0,$t_go = false){
  if ($type === 'ty')    $type = 0;
  if ($type === 'vx')    $type = 1;
  if ($type === 'qq')    $type = 2;
  
  global $DB,$conf;
  $num = $DB->count('SELECT count(domain) from uomg_domain WHERE status=1 and type ='.$type);
  return $num;
}
function GetDomain($type = 0,$t_go = false){
  if ($type === 'ty')    $type = 0;
  if ($type === 'vx')    $type = 1;
  if ($type === 'qq')    $type = 2;
  global $DB,$conf;

  if ($conf['jk_method'] == 'order') {
    $sql = ' limit 1';
  }elseif ($conf['jk_method'] == 'polling') {
    $sql = ' order by usetime asc limit 1';
  }
  if ($DB->count('SELECT count(domain) from uomg_enter WHERE status=1 and type ='.$type.$sql) > 0 && $t_go == true) {
    $urlstr = $DB->query('SELECT `domain` from uomg_enter WHERE status=1 and type ='.$type.$sql);
    while($res = $DB->fetch($urlstr)){
      $num[] = $res['domain'];
    }
    $ret_d = $num[array_rand($num,1)];
    $domain = str_replace('*.', getRandChar(mt_rand(1,9)).'.', $ret_d);
    $DB->query('update `uomg_enter` set usenum=usenum+1,usetime='.getMillisecond().' where domain ="'.$ret_d.'";');
    return $domain;
  }
  $urlstr = $DB->query('SELECT `domain` from uomg_domain WHERE status=1 and type ='.$type.$sql);
  while($res = $DB->fetch($urlstr)){
    $num[] = $res['domain'];
  }
  $ret_d = $num[array_rand($num,1)];
  $domain = str_replace('*.', getRandChar(mt_rand(1,9)).'.', $ret_d);
  $DB->query('update `uomg_domain` set usenum=usenum+1,usetime='.getMillisecond().' where domain ="'.$ret_d.'";');
  return $domain;
}
function getTempname($file,$type = 's'){
  if ($type == 's') {
    $tplData = implode('', @file(TEMPLATE.'stasis/'.$file));
    preg_match("/Template Name:([^\r\n]+)/i", $tplData, $name);
    return $name[1];
  }else{
    $tplData = implode('', @file(TEMPLATE.'home/'.$file));
    preg_match("/Template Name:([^\r\n]+)/i", $tplData, $name);
    return $name[1];
  }
}
function GetTempImages($file,$type = 's'){
  if ($type == 's') {
    $tplData = implode('', @file(TEMPLATE.'stasis/'.$file));
    preg_match("/Preview Url:([^\r\n]+)/i", $tplData, $previewurl);
    return $previewurl[1];
  }else{
    $tplData = implode('', @file(TEMPLATE.'home/'.$file));
    preg_match("/Preview Url:([^\r\n]+)/i", $tplData, $previewurl);
    return $previewurl[1];
  }
}
function getstasis() {
	global $conf;
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($ua, 'windows')!==false) {
    $s = str_replace('../','',$conf['stasis_other']);
  }elseif (strpos($ua, 'alipay')!==false){
    $s = str_replace('../','',$conf['stasis_alipay']);
  }elseif (strpos($ua, 'qq/')!==false) {
		if (strpos($ua, 'iphone')!==false || strpos($ua, 'ipod')!==false){
			$s = str_replace('../','',$conf['stasis_iosqq']);
		}elseif (strpos($ua, 'android')!==false){
			$s = str_replace('../','',$conf['stasis_anqq']);
		}else{
			$s = str_replace('../','',$conf['stasis_other']);
		}
	}elseif (strpos($ua, 'micromessenger')!==false){
		if (strpos($ua, 'iphone')!==false || strpos($ua, 'ipod')!==false){
      $s = str_replace('../','',$conf['stasis_ioswx']);
    }elseif (strpos($ua, 'android')!==false){
      $s = str_replace('../','',$conf['stasis_anwx']);
    }else{
      $s = str_replace('../','',$conf['stasis_other']);
    }
	}else{
		$s = str_replace('../','',$conf['stasis_other']);
	}
	return $s;
}
function QStasis($array) {
  $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
  if (strpos($ua, 'windows')!==false) {
    $s = str_replace('../','',$array['other']);
  }elseif (strpos($ua, 'alipay')!==false){
    $s = str_replace('../','',$array['alipay']);
  }elseif (strpos($ua, 'qq/')!==false) {
    if (strpos($ua, 'iphone')!==false || strpos($ua, 'ipod')!==false){
      $s = str_replace('../','',$array['ios_qq']);
    }elseif (strpos($ua, 'android')!==false){
      $s = str_replace('../','',$array['an_qq']);
    }else{
      $s = str_replace('../','',$array['other']);
    }
  }elseif (strpos($ua, 'micromessenger')!==false){
    if (strpos($ua, 'iphone')!==false || strpos($ua, 'ipod')!==false){
      $s = str_replace('../','',$array['ios_vx']);
    }elseif (strpos($ua, 'android')!==false){
      $s = str_replace('../','',$array['an_vx']);
    }else{
      $s = str_replace('../','',$array['other']);
    }
  }else{
    $s = str_replace('../','',$array['other']);
  }
  return $s;
}
?>