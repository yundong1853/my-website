<?php
#
#为了资金安全
#请手动修改易支付商户信息
#
//商户ID
$alipay_config['partner']       = $conf['epay_pid'];
//商户KEY
$alipay_config['key']           = $conf['epay_key'];
//签名方式 不需修改
$alipay_config['sign_type']    	= strtoupper('MD5');
//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']	= strtolower('utf-8');
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    	= 'http';
//支付API地址
$alipay_config['apiurl']    	= $conf['epay_url'];

