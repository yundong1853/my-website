<?php
/**
 * 短网址函数库
 * @copyright (c) UomgJump All Rights Reserved
 * @time 2020-03-25
 */
class Dwz{
    public static function short($longurl,$api) {
        switch ($api) {
            case 0:
                return $longurl;
                break;
            case 1:
                return self::many($longurl);
                break;
            case 2:
                return self::sinadwz($longurl);
                break;
            case 3:
                return self::tqqurl($longurl);
                break;
            case 4:
                return self::dwzcn($longurl);
                break;
            case 5:
                return self::suoim($longurl);
                break;
            case 6:
                return self::mrwso($longurl);
                break;
            case 7:
                return self::sinaurl($longurl);
                break;
            case 8:
                return self::sohugg($longurl);
                break;
            case 9:
                return self::wurlcn($longurl);
                break;
            case 10:
                return self::wxurlcn($longurl);
                break;
            case 11:
                return self::dwzla($longurl);
                break;
            case 12:
                return self::jqink($longurl);
                break;
            case 13:
                return self::r4gs($longurl);
                break;
            case 23:
                return self::leba($longurl);
                break;
            case 24:
                return self::zz3cn($longurl);
                break;
            case 66:
                return self::myurl($longurl);
                break;
            case 67:
                return self::oem_get($longurl);
                break;
            case 88:
                return self::wurlcn_fof($longurl);
                break;
            case 89:
                return self::tcn_oio($longurl);
                break;
            case 95:
                return self::wurlcn_uomg($longurl);
                break;
            case 96:
                return self::dwzcn_uomg($longurl);
                break;
            case 97:
                return self::urlcn_uomg($longurl);
                break;
            case 98:
                return self::tcn_uomg($longurl);
                break;
            case 99:
                return self::uomg($longurl);
                break;
            default:
                return $longurl;
                break;
        }
    }
    public static function many($longurl) {
        $rel .= self::tqqurl($longurl);
        $rel .= '<br />'.self::mrwso($longurl);
        $rel .= '<br />'.self::uomg($longurl);
        return $rel;
    }
    public static function sinadwz($longurl) {
        global $conf;
        $url = 'https://widget.weibo.com/dialog/PublishSimple.php?';
        $url .= http_build_query(array(
            'wsrc'          =>  'app_like'
            ,'app_src'      =>  '5srRh3'
            ,'content'      =>  ' '
            ,'source_link'  =>  $longurl
            ,'image_url'    =>  ''
        ));
        $result = self::_curl($url,0,$longurl,$conf['dwz_tcn_cookie']);
        preg_match('/<textarea  node-type="textEl" class="share_textarea">(.*?)<\/textarea>/i', $result, $matches);
        return isset($matches[1])?trim($matches[1]):$longurl;
    }
    public static function sinaurl($longurl) {
        $result = self::sinadwz($longurl);
        $result = str_replace('//t.cn/','//sinaurl.cn/',$result);
        return isset($result)?$result:$longurl;
    }
    public static function sinalong($url) {
        $appkey = '2815391962';
        $url='https://api.t.sina.com.cn/short_url/expand.json?source='.$appkey.'&url_short='.urlencode($url);
        $result = self::_curl($url);
        $arr = json_decode($result, true);
        return isset($arr[0]['url_long'])?$arr[0]['url_long']:$longurl;
    }
    public static function dwzcn($longurl) {
        global $conf;
        $post = array(
            'Url'               =>  $longurl
            , 'TermOfValidity'  =>  'long-term'
        );
        $headers = array(
            'Content-Type:application/json'
            , 'Token:'.$conf['dwz_dwzcn_token']
        );
        $curl = curl_init('http://dwz.cn/admin/v2/create');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post));
        $result = curl_exec($curl);
        curl_close($curl);
        if (empty($result)) return $longurl;
        $arr = json_decode($result, true);
        return isset($arr['ShortUrl'])?$arr['ShortUrl']:$arr['ErrMsg'];
    }
    public static function tqqurl($longurl) {
        $query = http_build_query(array(
            'type'  =>  2 ,
            'url'   =>  $longurl
        ));
        $result = file_get_contents('https://api.oioweb.cn/api/dwz.php?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['url'])?$arr['url']['url_short']:$longurl;
    }
    public static function suoim($longurl) {
        global $conf;
        $query = http_build_query(array(
            'url'       =>  $longurl
            ,'format'   =>  'json'
            ,'key'      =>  $conf['dwz_suoim_token']
        ));
        $rel=self::_curl('http://suo.im/api.htm?'.$query);
        $rel=json_decode($rel,true);
        return !empty($rel['url'])?$rel['url']:$rel['err'];
    }
    public static function mrwso($longurl) {
        global $conf;
        $query = http_build_query(array(
            'url'       =>  $longurl
            ,'format'   =>  'json'
            ,'key'      =>  $conf['dwz_mrwso_token']
        ));
        $rel=self::_curl('http://mrw.so/api.php?'.$query);
        $rel=json_decode($rel,true);
        return !empty($rel['url'])?$rel['url']:$rel['err'];
    }
    public static function sohugg($longurl) {
        $query = http_build_query(array(
            'key'       =>  'oLN21gFJxDaI'
            ,'url'      =>  $longurl
            ,'format'   =>  'json'
        ));
        $rel=self::_curl('https://sohu.gg/api/?'.$query);
        $rel=json_decode($rel,true);
        return isset($rel['short'])?$rel['short']:$longurl;
    }
    public static function wurlcn($longurl) {
        global $conf;
        $WeChat = new WeChat($conf['dwz_wechat_appid'],$conf['dwz_wechat_secret']);
        $dwz = $WeChat->long2short($longurl);
        return $dwz;
    }
    public static function wxurlcn($longurl) {
        $query = http_build_query(array(
            'long_url'  =>  $longurl
        ));
        $rel=self::_curl('http://wxurl.cn/v1/api/short_url?'.$query);
        $rel=json_decode($rel,true);
        return isset($rel['short_url'])?$rel['short_url']:$longurl;
    }
    public static function dwzla($longurl) {
        $post = http_build_query(array(
            'url'       =>  $longurl
        ));
        $rel=self::_curl('http://dwz.la/index.php?m=index&a=create',$post);
        $arr=json_decode($rel,true);
        return isset($arr['tinyurl'])?$arr['tinyurl']:$longurl;
    }
    public static function jqink($longurl) {
        $post = http_build_query(array(
            'url'       =>  $longurl
        ));
        $rel=self::_curl('http://jq.ink/index.php?m=Index&a=create',$post);
        $arr=json_decode($rel,true);
        return isset($arr['tinyurl'])?$arr['tinyurl']:$longurl;
    }
    public static function r4gs($longurl) {
        $post = http_build_query(array(
            'key'      =>  '6114d70c3b54c0f8d97f68e0325120'
            ,'url'       =>  $longurl
        ));
        $rel=self::_curl('http://r4.gs/api/v2/action/shorten',$post);
        return isset($rel)?$rel:$longurl;
    }
    public static function leba($longurl) {
        $url = 'https://68x.cn/api/new?long_url='.urlencode($longurl);
        $data = self::_curl($url);
        $arr = json_decode($data, true);
        return isset($arr['msg'])?$arr['msg']:false;
    }
    public static function zz3cn($longurl) {
        $url = 'https://zz3.cn/api/new?long_url='.urlencode($longurl);
        $data = self::_curl($url);
        $arr = json_decode($data, true);
        return isset($arr['msg'])?$arr['msg']:false;
    }
    public static function myurl($longurl) {
        global $conf;
        $post = http_build_query(array(
            'key'      =>  '6114d70c3b54c0f8d97f68e0325120'
            ,'url'       =>  $longurl
        ));
        $rel = self::_curl($conf['dwz_myurl_siteurl'].'api.php',$post);
        $rel = trim(self::DoBOM($rel));
        $arr = json_decode($rel,true);
        return isset($arr['code'])?$conf['dwz_myurl_siteurl'].$arr['code']:$longurl;
    }
    public static function oem_get($longurl) {
        global $conf;
        $rel = self::_curl($conf['dwz_get_api'].urlencode($longurl));
        $rel = trim(self::DoBOM($rel));
        $array = json_decode($rel,true);
        return isset($array[$conf['dwz_get_param']])?$array[$conf['dwz_get_param']]:$longurl;
    }
    public static function uomg($longurl) {
        $file_name = __DIR__.'/dwz.txt';
        $mtime = @filemtime($file_name);
        if ($mtime + 86400 > time()) {
            $json = file_get_contents($file_name);
        }else{
            $json = file_get_contents('http://uomgjump-1251492153.cos.ap-shanghai.myqcloud.com/dwz.json');
            file_put_contents($file_name, $json);
        }
        $conf = json_decode($json,true);
        if (!is_array($conf))   return '短网址失效';
        parse_str($conf['param'],$array);
        $param = http_build_query(str_replace('{uomg}', $longurl, $array));
        if ($conf['method'] == 'post') {
            $rel = self::_curl($conf['url'],$param);
        }else{
            $rel = self::_curl($conf['url'].$param);
        }
        $rel = trim(self::DoBOM($rel));
        $array = json_decode($rel,true);
        return $array[$conf['key']];
    }
    public static function wurlcn_fof($longurl) {
        /*$result = self::_curl('http://fof.ink/');
        preg_match('/name="__HASH__" value="(.*?)">/i', $result, $matches);
        */
        $post = http_build_query(array(
            'url'       =>  $longurl
            ,'sort_type'=>  'url_cn'
            //,'__HASH__' =>  $matches[1]
        ));
        $result = self::_curl('http://fof.ink/index/start.html', $post);
        preg_match('/<span class="col-blue tuiguanglink" id="tuiguanglink3" style="color: #0096ff">(.*?)<\/span>/i', $result, $matches);
        return isset($matches[1])?$matches[1]:$longurl;
    }
    public static function tcn_oio($longurl) {
        global $conf;
        $query = http_build_query(array(
            'url'     =>  $longurl
        ));
        $result = file_get_contents('https://api.oioweb.cn/api/dwz.php?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['url'])?$arr['url']['url_short']:$longurl;
    }
    public static function wurlcn_uomg($longurl) {
        global $conf;
        $query = http_build_query(array(
            'longurl'     =>  $longurl
            ,'token'      =>  $conf['dwz_uomg_token']
        ));
        $result = file_get_contents('http://check.uomg.com/api/dwz/wurlcn?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['ae_url'])?$arr['ae_url']:$arr['msg'];
    }
    public static function dwzcn_uomg($longurl) {
        global $conf;
        $query = http_build_query(array(
            'longurl'     =>  $longurl
            ,'token'      =>  $conf['dwz_uomg_token']
        ));
        $result = file_get_contents('http://check.uomg.com/api/dwz/dwzcn?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['ae_url'])?$arr['ae_url']:$arr['msg'];
    }
    public static function urlcn_uomg($longurl) {
        global $conf;
        $query = http_build_query(array(
            'longurl'     =>  $longurl
            ,'token'      =>  $conf['dwz_uomg_token']
        ));
        $result = file_get_contents('http://check.uomg.com/api/dwz/urlcn?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['ae_url'])?$arr['ae_url']:$arr['msg'];
    }
    public static function tcn_uomg($longurl) {
        global $conf;
        $query = http_build_query(array(
            'longurl'     =>  $longurl
            ,'token'      =>  $conf['dwz_uomg_token']
        ));
        $result = file_get_contents('http://check.uomg.com/api/dwz/tcn?'.$query);
        $arr = json_decode($result, true);
        return isset($arr['ae_url'])?$arr['ae_url']:$arr['msg'];
    }
    private static function DoBOM($text){
        if(substr($text, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) $text = substr($text, 3);
        return $text;
    }
    private static function getSubstr($str, $leftStr, $rightStr){
        $left = strpos($str, $leftStr);
        //echo '左边:'.$left;
        $right = strpos($str, $rightStr,$left);
        //echo '<br>右边:'.$right;
        if($left < 0 or $right < $left) return '';
        return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
    }
    private static function _curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0){
        $ch = curl_init();
        $ip = rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255) ;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $httpheader[] = "Accept:application/json";
        $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
        $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
        $httpheader[] = "Connection:close";
        $httpheader[] = 'CLIENT-IP:'.$ip;
        $httpheader[] = 'X-FORWARDED-FOR:'.$ip;
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
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }
}   