<?php
/*!
@name:防洪首页文件
@description:防洪首页调用文件
@author:墨渊 
@version:4.7
@time:2020-03-25
@copyright:优启梦&墨渊
*/
include './includes/common.php';

if ($conf['jk_home'] == 0 && parse_url($conf['siteurl'])['host'] != $_SERVER['HTTP_HOST']){
  include TEMPLATE.'tips/close.html';
  exit();
}

if ($conf['homestyle'] == 'random') {
  $handle = @opendir(TEMPLATE.'home/') OR die('uomgjump template path error!');
  while ($file = @readdir($handle)) {
    if( "." != $file && ".." != $file ) {
      $files[] = $file;
    }
  }
  closedir($handle);
  $name=str_replace('../','',$files[array_rand($files,1)]);
}else{
  $name=str_replace('../','',$conf['homestyle']);
}

$h = file_get_contents(TEMPLATE.'home/'.$name);
include TEMPLATE.'home.conf';
foreach ($home[$name] as $key => $val) {  
    $h = str_replace('{'.$key.'}',$val['values'],$h);
}
$aa = array('{name}','{siteurl}','{sitename}','{keywords}','{description}','{cnzzid}','{bdtjid}','{kfqq}','{dwzapi}','{dwztype}','{qid}');
$bb = array($conf['name'],$conf['siteurl'],$conf['sitename'],$conf['keywords'],$conf['description'],$conf['cnzzid'],$conf['bdtjid'],$conf['kfqq'],$conf['dwzapi'],$conf['dwz_type'],$conf['qid']);

$html = str_replace($aa,$bb,$h);
if ($conf['show_home'] == 0) $html = str_replace('<body','<body style="display: none;"',$html);

echo $html;
//include './template/home/'.$home;
?>
