<?php
/**
 * UID函数库
 * @copyright (c) UomgJump All Rights Reserved
 * @time 2018-08-12
 */
class Uid{
    public static function short($text,$type = 0,$rand = false){
        if ($type == 1) {
            return self::reckon($text,$rand);
        }else {
            return self::sina($text);
        }
    }
    private static function sina($longurl) {
        $appkey = '31641035';
        $url='https://api.weibo.com/2/short_url/shorten.json?source='.$appkey.'&url_long='.urlencode($longurl);
        $result = self::_curl($url);
        $arr = json_decode($result, true);
        return str_replace('http://t.cn/', '',$arr['urls'][0]['url_short']);
    }
    private static function reckon($input,$rand){
        $base32 = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5');
        if ($rand == true) shuffle($base32);
        $hex = md5($input);
        $hexLen = strlen($hex);
        $subHexLen = $hexLen / 8;
        $output = array();
        for ($i = 0; $i < 4; $i++) {
            //把加密字符按照8位一组16进制与0x3FFFFFFF(30位1)进行位与运算
            $subHex = substr($hex, $i * 8, 8);
            $int = hexdec($subHex) & 0x3fffffff;
            //$int = 0x3fffffff & 1 * ('0x' . $subHex);
            $out = '';
            for ($j = 0; $j < 6; $j++) {
                //把得到的值与0x0000001F进行位与运算，取得字符数组chars索引
                $val = 0x1f & $int;
                $out .= $base32[$val];
                $int = $int >> 5;
            }
            $output[] = $out;
        }
        return $output[0];
    }
    private static function _curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $httpheader[] = "Accept:application/json";
        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
        $httpheader[] = "Connection:close";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HEADER, true);
        }
        if ($cookie) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        }
        if($referer){
            if($referer==1){
                curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
            }else{
                curl_setopt($ch, CURLOPT_REFERER, $referer);
            }
        }
        if ($ua) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        }
        else {
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; U; Android 4.0.4; es-mx; HTC_One_X Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0");
        }
        if ($nobaody) {
            curl_setopt($ch, CURLOPT_NOBODY, 1);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}