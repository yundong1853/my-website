<?php
/*!
@name:二维码文件
@description:二维码接口文件
@author:墨渊 
@version:3.6
@time:2018-08-12
@copyright:优启梦&墨渊
*/
include './includes/model/qrcode.class.php';

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods:GET,POST");
header('Access-Control-Allow-Headers:x-requested-with,content-type');
header("Content-Type: image/png; charset=utf-8");


$url = (isset($_GET['url']))?$_GET['url']:$_POST['url'];
if(!isset($url))    exit('{"msg":"URL不能为空"}');


// 纠错级别：L、M、Q、H
$level = 'L';
// 点的大小：1到10,用于手机端4就可以了
$size = 6;
QRcode::png($url, false, $level, $size);