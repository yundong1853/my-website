<?php
// +----------------------------------------------------------------------
// | UomgJump [优启梦域名防洪]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2019 Uomg All rights reserved.
// +----------------------------------------------------------------------

global $dbconfig,$config,$mail;
/*数据库配置*/
$dbconfig=array(
    //数据库地址
    'host'      => '127.0.0.1',
    //数据库端口
    'port'      => '3306',
    //数据库用户名
    'user'      => 'root',
    //数据库密码
    'pwd'       => 'root',
    //数据库名
    'dbname'    => 'uomg'
);
/*网站配置*/
$config=array(
    //管理员用户名
    'admin_user'    => 'admin', 
    //管理员密码
    'admin_pwd'     => 'admin' 
);
/*邮箱配置*/
$mail=array(
    //邮件服务器地址
    'smtp' => 'smtp.exmail.qq.com', 
    //邮件服务器端口
    'port' => 25,
    //发信邮箱帐号
    'name' => 'admin@aeink.com', 
    //发信邮箱密码（授权码）
    'pass' => 'Password',
    //收信邮箱
    'addressee' => 'admin@uomg.com' 
);
/*系统配置*/
//缓存方式(0为数据库1为文件)
define('CACHE_FILE', 0);

//QQ二维码(0为默认1为备用)
define('QQ_QRCODE', 1);
?>