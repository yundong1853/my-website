<?php
/**
 * 基础函数库
 * @copyright (c) UomgJump All Rights Reserved
 * @time 2018-08-12
 */
function __autoload($class) {
	$class = strtolower($class);
	if (file_exists(SYSTEM_ROOT . '/model/' . $class . '.class.php')) {
		require_once(SYSTEM_ROOT . '/model/' . $class . '.class.php');
	} elseif (file_exists(SYSTEM_ROOT . '/libs/' . $class . '.class.php')) {
		require_once(SYSTEM_ROOT . '/libs/' . $class . '.class.php');
	} else {
		sysmsg($class . '类加载失败。');
	}
}
function _load($fun) {
	$fun = strtolower($fun);
	if (file_exists(SYSTEM_ROOT . '/model/' . $fun . '.php')) {
		require_once(SYSTEM_ROOT . '/model/' . $fun . '.php');
	} elseif (file_exists(SYSTEM_ROOT . '/libs/' . $fun . '.php')) {
		require_once(SYSTEM_ROOT . '/libs/' . $fun . '.php');
	} else {
		sysmsg($fun . '加载失败。');
	}
}
function curl_get($url){
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 4.4.1; zh-cn; R815T Build/JOP40D) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1');
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	$content=curl_exec($ch);
	curl_close($ch);
	return($content);
}
function get_curl($url, $post=0, $referer=0, $cookie=0, $header=0, $ua=0, $nobaody=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Content-Type:application/x-www-form-urlencoded";
	//$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
	//$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	//$httpheader[] = "Connection:close";
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
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function real_ip(){
    $ip = $_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}
function get_ip_city($ip){
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=';
    @$city = curl_get($url . $ip);
    $city = json_decode($city, true);
    if ($city['city']) {
        $location = $city['province'].$city['city'];
    } else {
        $location = $city['province'];
    }
	if($location){
		return $location;
	}else{
		return false;
	}
}
function get_ip_taobao($ip){
	$url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
    $data = curl_get($url);
	$arr = json_decode($data, true);
	$arr = $arr['data'];
	$location = $arr['region'].$arr['city'];
	return $location;
}
//IP138接口
function get_ip_ip138($ip){
	$url = 'http://www.ip138.com/ips138.asp?ip='.$ip.'&action=2';
    $data = curl_get($url);
	$data=mb_convert_encoding($data, "UTF-8", "GB2312");
	preg_match('!本站主数据：(.*?)</li>!i',$data,$location);
	$location = $location[1];
	return $location;
}
function daddslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}
function strexists($string, $find) {
	return !(strpos($string, $find) === FALSE);
}
function dstrpos($string, $arr) {
	if(empty($string)) return false;
	foreach((array)$arr as $v) {
		if(strpos($string, $v) !== false) {
			return true;
		}
	}
	return false;
}
function jsonp_decode($jsonp, $assoc = false){
    $jsonp = trim($jsonp);
    if(isset($jsonp[0]) && $jsonp[0] !== '[' && $jsonp[0] !== '{') {
        $begin = strpos($jsonp, '(');
        if(false !== $begin)
        {
            $end = strrpos($jsonp, ')');
            if(false !== $end)
            {
                $jsonp = substr($jsonp, $begin + 1, $end - $begin - 1);
            }
        }
    }
    return json_decode($jsonp, $assoc);
}
function eraselogin(){
	unset($_SESSION[$_SERVER['REMOTE_ADDR']]);
    foreach ($_COOKIE as $key => $value) {
        setcookie($key, null);
    }
}
function checkmobile() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ualist = array('android', 'midp', 'nokia', 'mobile', 'iphone', 'ipod', 'blackberry', 'windows phone');
	if((dstrpos($useragent, $ualist) || strexists($_SERVER['HTTP_ACCEPT'], "VND.WAP") || strexists($_SERVER['HTTP_VIA'],"wap")))
		return true;
	else
		return false;
}
function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key ? $key : ENCRYPT_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function UoZip($zipfile) {
	if (!class_exists('ZipArchive', FALSE)) {
		return 3;//zip模块问题
	}
	$zip = new ZipArchive();
	if ($zip->open($zipfile) !== TRUE) {
		return 2;//文件权限问题
	}
	if ($zip->getFromName('template/home/install.uomg') !== false) {
		$code = -1;
	}elseif ($zip->getFromName('template/stasis/install.uomg') !== false) {
		$code = -2;
	}else{
		return -3; //非主题安装包
	}
	if ($zip->extractTo(ROOT) === TRUE) {
		$zip->close();
		return $code;
	} else {
		return 1;//文件权限问题
	}

}
function getFileSuffix($fileName) {
	return strtolower(pathinfo($fileName,  PATHINFO_EXTENSION));
}
function random($length, $numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $hash;
}
function getBaseDomain($url='',$type = 'domain'){
	if(!$url)  return $url[$type];
	#列举域名中固定元素
	$state_domain = array('aaa','aarp','abarth','abb','abbott.abbvie','abc','able','abogado','abudhabi.ac','academy','accenture','accountant','accountants.aco','actor','ad','adac','ads.adult','ae','aeg','aero','aetna.af','afamilycompany','afl','africa','ag.agakhan','agency','ai','aig','aigo.airbus','airforce','airtel','akdn','al.alfaromeo','alibaba','alipay','allfinanz','allstate.ally','alsace','alstom','am','americanexpress.americanfamily','amex','amfam','amica','amsterdam.analytics','android','anquan','anz','ao.aol','apartments','app','apple','aq.aquarelle','ar','arab','aramco','archi.army','arpa','art','arte','as.asda','asia','associates','at','athleta.attorney','au','auction','audi','audible.audio','auspost','author','auto','autos.avianca','aw','aws','ax','axa.az','azure','ba','baby','baidu','banamex','bananarepublic.band','bank','bar','barcelona','barclaycard.barclays','barefoot','bargains','baseball','basketball.bauhaus','bayern','bb','bbc','bbt.bbva','bcg','bcn','bd','be.beats','beauty','beer','bentley','berlin.best','bestbuy','bet','bf','bg.bh','bharti','bi','bible','bid.bike','bing','bingo','bio','biz.bj','black','blackfriday','blockbuster','blog.bloomberg','blue','bm','bms','bmw.bn','bnpparibas','bo','boats','boehringer.bofa','bom','bond','boo','book.booking','bosch','bostik','boston','bot.boutique','box','br','bradesco','bridgestone.broadway','broker','brother','brussels','bs.bt','budapest','bugatti','build','builders.business','buy','buzz','bv','bw.by','bz','bzh','ca','cab','cafe','cal','call.calvinklein','cam','camera','camp','cancerresearch.canon','capetown','capital','capitalone','car.caravan','cards','care','career','careers.cars','casa','case','caseih','cash.casino','cat','catering','catholic','cba.cbn','cbre','cbs','cc','cd.ceb','center','ceo','cern','cf.cfa','cfd','cg','ch','chanel.channel','charity','chase','chat','cheap.chintai','christmas','chrome','church','ci.cipriani','circle','cisco','citadel','citi.citic','city','cityeats','ck','cl.claims','cleaning','click','clinic','clinique.clothing','cloud','club','clubmed','cm.cn','co','coach','codes','coffee.college','cologne','com','comcast','commbank.community','company','compare','computer','comsec.condos','construction','consulting','contact','contractors.cooking','cookingchannel','cool','coop','corsica.country','coupon','coupons','courses','cpa.cr','credit','creditcard','creditunion','cricket.crown','crs','cruise','cruises','csc.cu','cuisinella','cv','cw','cx.cy','cymru','cyou','cz','dabur','dad','dance','data','date.dating','datsun','day','dclk','dds.de','deal','dealer','deals','degree.delivery','dell','deloitte','delta','democrat.dental','dentist','desi','design','dev.dhl','diamonds','diet','digital','direct.directory','discount','discover','dish','diy.dj','dk','dm','dnp','do.docs','doctor','dog','domains','dot.download','drive','dtv','dubai','duck.dunlop','dupont','durban','dvag','dvr.dz','earth','eat','ec','eco','edeka.edu','education','ee','eg','email.emerck','energy','engineer','engineering','enterprises.epson','equipment','er','ericsson','erni.es','esq','estate','esurance','et.etisalat','eu','eurovision','eus','events.exchange','expert','exposed','express','extraspace','fage','fail','fairwinds','faith','family.fan','fans','farm','farmers','fashion.fast','fedex','feedback','ferrari','ferrero.fi','fiat','fidelity','fido','film.final','finance','financial','fire','firestone.firmdale','fish','fishing','fit','fitness.fj','fk','flickr','flights','flir.florist','flowers','fly','fm','fo.foo','food','foodnetwork','football','ford.forex','forsale','forum','foundation','fox.fr','free','fresenius','frl','frogans.frontdoor','frontier','ftr','fujitsu','fujixerox.fun','fund','furniture','futbol','fyi','ga','gal','gallery','gallo','gallup.game','games','gap','garden','gay.gb','gbiz','gd','gdn','ge.gea','gent','genting','george','gf.gg','ggee','gh','gi','gift.gifts','gives','giving','gl','glade.glass','gle','global','globo','gm.gmail','gmbh','gmo','gmx','gn.godaddy','gold','goldpoint','golf','goo.goodyear','goog','google','gop','got.gov','gp','gq','gr','grainger.graphics','gratis','green','gripe','grocery.group','gs','gt','gu','guardian.gucci','guge','guide','guitars','guru.gw','gy','hair','hamburg','hangout','haus','hbo.hdfc','hdfcbank','health','healthcare','help.helsinki','here','hermes','hgtv','hiphop.hisamitsu','hitachi','hiv','hk','hkt.hm','hn','hockey','holdings','holiday.homedepot','homegoods','homes','homesense','honda.horse','hospital','host','hosting','hot.hoteles','hotels','hotmail','house','how.hr','hsbc','ht','hu','hughes.hyatt','hyundai','ibm','icbc','ice','icu','id.ie','ieee','ifm','ikano','il.im','imamat','imdb','immo','immobilien.in','inc','industries','infiniti','info.ing','ink','institute','insurance','insure.int','intel','international','intuit','investments.io','ipiranga','iq','ir','irish.is','ismaili','ist','istanbul','it.itau','itv','iveco','jaguar','java','jcb','jcp','je.jeep','jetzt','jewelry','jio','jll.jm','jmp','jnj','jo','jobs.joburg','jot','joy','jp','jpmorgan.jprs','juegos','juniper','kaufen','kddi','ke','kerryhotels','kerrylogistics.kerryproperties','kfh','kg','kh','ki.kia','kim','kinder','kindle','kitchen.kiwi','km','kn','koeln','komatsu.kosher','kp','kpmg','kpn','kr.krd','kred','kuokgroup','kw','ky.kyoto','kz','la','lacaixa','lamborghini','lamer','lancaster.lancia','land','landrover','lanxess','lasalle.lat','latino','latrobe','law','lawyer.lb','lc','lds','lease','leclerc.lefrak','legal','lego','lexus','lgbt.li','lidl','life','lifeinsurance','lifestyle.lighting','like','lilly','limited','limo.lincoln','linde','link','lipsy','live.living','lixil','lk','llc','llp.loan','loans','locker','locus','loft.lol','london','lotte','lotto','love.lpl','lplfinancial','lr','ls','lt.ltd','ltda','lu','lundbeck','lupin.luxe','luxury','lv','ly','ma','macys','madrid','maif','maison.makeup','man','management','mango','map.market','marketing','markets','marriott','marshalls.maserati','mattel','mba','mc','mckinsey.md','me','med','media','meet.melbourne','meme','memorial','men','menu.merckmsd','metlife','mg','mh','miami.microsoft','mil','mini','mint','mit.mitsubishi','mk','ml','mlb','mls.mm','mma','mn','mo','mobi.mobile','moda','moe','moi','mom.monash','money','monster','mormon','mortgage.moscow','moto','motorcycles','mov','movie.mp','mq','mr','ms','msd.mt','mtn','mtr','mu','museum.mutual','mv','mw','mx','my.mz','na','nab','nadex','nagoya','name.nationwide','natura','navy','nba','nc.ne','nec','net','netbank','netflix.network','neustar','new','newholland','news.next','nextdirect','nexus','nf','nfl.ng','ngo','nhk','ni','nico.nike','nikon','ninja','nissan','nissay.nl','no','nokia','northwesternmutual','norton.now','nowruz','nowtv','np','nr.nra','nrw','ntt','nu','nyc.nz','obi','observer','off','office','okinawa.olayan','olayangroup','oldnavy','ollo','om.omega','one','ong','onl','online.onyourside','ooo','open','oracle','orange.org','organic','origins','osaka','otsuka.ott','ovh','pa','page','panasonic','paris','pars.partners','parts','party','passagens','pay.pccw','pe','pet','pf','pfizer.pg','ph','pharmacy','phd','philips.phone','photo','photography','photos','physio.pics','pictet','pictures','pid','pin.ping','pink','pioneer','pizza','pk.pl','place','play','playstation','plumbing.plus','pm','pn','pnc','pohl.poker','politie','porn','post','pr.pramerica','praxi','press','prime','pro.prod','productions','prof','progressive','promo.properties','property','protection','pru','prudential.ps','pt','pub','pw','pwc.py','qa','qpon','quebec','quest','qvc','racing','radio','raid','re','read.realestate','realtor','realty','recipes','red.redstone','redumbrella','rehab','reise','reisen.reit','reliance','ren','rent','rentals.repair','report','republican','rest','restaurant.review','reviews','rexroth','rich','richardli.ricoh','rightathome','ril','rio','rip.rmit','ro','rocher','rocks','rodeo.rogers','room','rs','rsvp','ru.rugby','ruhr','run','rw','rwe.ryukyu','sa','saarland','safe','safety','sakura.sale','salon','samsclub','samsung','sandvik.sandvikcoromant','sanofi','sap','sarl','sas.save','saxo','sb','sbi','sbs.sc','sca','scb','schaeffler','schmidt.scholarships','school','schule','schwarz','science.scjohnson','scor','scot','sd','se.search','seat','secure','security','seek.select','sener','services','ses','seven.sew','sex','sexy','sfr','sg.sh','shangrila','sharp','shaw','shell.shia','shiksha','shoes','shop','shopping.shouji','show','showtime','shriram','si.silk','sina','singles','site','sj.sk','ski','skin','sky','skype.sl','sling','sm','smart','smile.sn','sncf','so','soccer','social.softbank','software','sohu','solar','solutions.song','sony','soy','space','sport.spot','spreadbetting','sr','srl','ss.st','stada','staples','star','statebank.statefarm','stc','stcgroup','stockholm','storage.store','stream','studio','study','style.su','sucks','supplies','supply','support.surf','surgery','suzuki','sv','swatch.swiftcover','swiss','sx','sy','sydney.symantec','systems','sz','tab','taipei','talk','taobao','target.tatamotors','tatar','tattoo','tax','taxi.tc','tci','td','tdk','team.tech','technology','tel','temasek','tennis.teva','tf','tg','th','thd.theater','theatre','tiaa','tickets','tienda.tiffany','tips','tires','tirol','tj.tjmaxx','tjx','tk','tkmaxx','tl.tm','tmall','tn','to','today.tokyo','tools','top','toray','toshiba.total','tours','town','toyota','toys.tr','trade','trading','training','travel.travelchannel','travelers','travelersinsurance','trust','trv.tt','tube','tui','tunes','tushu.tv','tvs','tw','tz','ua','ubank','ubs','ug','uk.unicom','university','uno','uol','ups.us','uy','uz','va','vacations','vana','vanguard','vc.ve','vegas','ventures','verisign','versicherung.vet','vg','vi','viajes','video.vig','viking','villas','vin','vip.virgin','visa','vision','vistaprint','viva.vivo','vlaanderen','vn','vodka','volkswagen.volvo','vote','voting','voto','voyage.vu','vuelos','wales','walmart','walter','wang','wanggou.watch','watches','weather','weatherchannel','webcam.weber','website','wed','wedding','weibo.weir','wf','whoswho','wien','wiki.williamhill','win','windows','wine','winners.wme','wolterskluwer','woodside','work','works.world','wow','ws','wtc','wtf','xbox','xerox','xfinity','xihuan','xin','xin.xxx','xyz','yachts','yahoo','yamaxun','yandex','ye.yodobashi','yoga','yokohama','you','youtube.yt','yun','za','zappos','zara','zero','zip.zm','zone','zuerich','zw','cn','net.cn','org.cn','org','vip','gg'
	);
	if(!preg_match("/^http/is", $url)) $url="http://".$url;
	
	$res = null;
	$res['domain'] = null;
	$res['host'] = null;
	$url_parse = parse_url(strtolower($url));
	$urlarr = explode(".", $url_parse['host']);
	$count = count($urlarr);

	$res['host'] = $url_parse['host'];
	if($count <= 2){
	    #当域名直接根形式不存在host部分直接输出
	    $res['domain'] = $url_parse['host'];
	}elseif($count > 2){
	    $last = array_pop($urlarr);
	    $last_1 = array_pop($urlarr);
	    $last_2 = array_pop($urlarr);
	      
	    $res['domain'] = $last_1.'.'.$last;

	    if(in_array($last, $state_domain)){
	      $res['domain'] = $last_1.'.'.$last;
	      return $res[$type];
	    }
	    if(in_array($last_1.'.'.$last, $state_domain)){
	      $res['domain'] = $last_2.'.'.$last_1.'.'.$last;
	      return $res[$type];
	    }
	    #print_r(get_defined_vars());die;
	}
	return $res[$type];
}
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = ''; 
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding ); 
            if (strlen ( $str ) > 1) { // 多字节字符 
                $return .= '%'.'u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) ); 
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) ); 
            } 
        } 
    } 
    return $return; 
}
function getqq($uin){
    for($i = 0; $i < strlen($uin); $i++){
			if($uin[$i]=='o'||$uin[$i]=='0')continue;
			else break;
    }
    return substr($uin,$i);
}
function getRandChar2($length){
   $str = null;
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;

   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }

   return $str;
}
function getRandChar($length){
   $str = null;
   $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
   $max = strlen($strPol)-1;

   for($i=0;$i<$length;$i++){
    $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
   }

   return $str;
}
/**
 * 获取GET或POST过来的参数
 * @param $key 键值
 * @param $default 默认值
 * @return 获取到的内容（没有则为默认值）
 */
function getParam($key,$default=''){
    return trim($key && is_string($key) ? (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default)) : $default);
}
function showjmsg($code,$msg){
    ob_end_clean();
    $result=array(
      'code'=>$code,
      'msg'=>$msg
    );
    print_r(json_encode($result));
    exit;
}

function showmsg($content = '未知的异常',$type = 4,$back = false)
{
switch($type)
{
case 1:
	$panel="success";
break;
case 2:
	$panel="info";
break;
case 3:
	$panel="warning";
break;
case 4:
	$panel="danger";
break;
}

echo '<div class="panel panel-'.$panel.'">
      <div class="panel-heading">
        <h3 class="panel-title">提示信息</h3>
        </div>
        <div class="panel-body">';
echo $content;

if ($back) {
	echo '<hr/><a href="'.$back.'"><< 返回上一页</a>';
}
else
    echo '<hr/><a href="javascript:history.back(-1)"><< 返回上一页</a>';

echo '</div>
    </div>';
}
function showError($msg = '未知的异常',$qq = 774740085,$qid = 295023774) {
?> 
<!DOCTYPE html><html lang='zh-cn'><head><meta charset='UTF-8'><meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<title><?=$msg;?></title><link rel='stylesheet' href='https://cdn.bootcss.com/normalize/5.0.0/normalize.min.css'><style>html,body {height: 100%;margin: 0;padding: 0;}body {font-family: 'lucida grande', 'lucida sans unicode', lucida, helvetica, 'Hiragino Sans GB', 'Microsoft YaHei', 'WenQuanYi Micro Hei', sans-serif;align-items: center;display: flex;}a{text-decoration:none;}#container {max-width: 400px;flex-basis: 100%;margin: 0 auto;background: #FFF;border-radius: 10px;box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);-webkit-box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);overflow: hidden;}#container #hero-img {width: 100%;height: 200px;background: #007bff;}#container #profile-img {width: 160px;height: 160px;margin: -80px auto 0 auto;border: 6px solid #FFF;border-radius: 50%;box-shadow: 0 0 5px rgba(90, 90, 90, 0.3);}#container #profile-img img {width: 100%;background: #FFF;border-radius: 50%;}#container #content {text-align: center;width: 320px;margin: 0 auto;padding: 0 0 50px 0;}#container #content h1 {font-size: 29px;font-weight: 500;margin: 50px 0 0 0;}#container #content p {font-size: 18px;font-weight: 400;line-height: 1.4;color: #666;margin: 15px 0 40px 0;}#container #content a {color: #CCC;font-size: 14px;margin: 0 10px;transition: color .3s ease-in-out;-webkit-transition: color .3s ease-in-out;}#container #content a:hover {color: #007bff;}.btn{background: none repeat scroll 0 0 #1BA1E2; border: 0 none; border-radius: 2px; color: #FFFFFF !important; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px;  padding: 6px 10%;}.btn:hover,.yanshibtn:hover{background: none repeat scroll 0 0 #9B59B6; border: 0 none; border-radius: 2px; color: #FFFFFF!important; cursor: pointer; font-family: "Open Sans","Hiragino Sans GB","Microsoft YaHei","WenQuanYi Micro Hei",Arial,Verdana,Tahoma,sans-serif; font-size: 14px; padding: 8px 10%;}</style></head><body><div id='container'><div id='hero-img'></div><div id='profile-img'><img src='https://ws3.sinaimg.cn/large/005BYqpgly1fua355p7n6j30m80m8myd.jpg'/></div><div id='content'><h1><?=$msg;?></h1><p>请联系QQ<?=$qq;?></p><a href='http://wpa.qq.com/msgrd?v=3&uin=<?=$qq;?>&site=qq&menu=yes' target="_blank" class="btn btn-default" rel='nofollow'>联系站长</a><a href='./api.php?method=joinqun&qun=<?=$qid;?>' target="_blank" class="btn btn-default" rel='nofollow'>官方Ｑ群</a></div></div></body></html>
<?php
}
function sysmsg($msg = '未知的异常',$die = true) {
    ?>  
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>站点提示信息</title>
        <style type="text/css">
html{background:#eee}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
        </style>
    </head>
    <body id="error-page">
        <?php echo '<h3>站点提示信息</h3>';
        echo $msg; ?>
    </body>
    </html>
    <?php
    if ($die == true) {
        exit;
    }
}
?>