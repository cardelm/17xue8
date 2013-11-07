<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$site_info = C::t(GM('server_site'))->fetch($indata['siteurl']);
if($site_info['sitegroup'] == 'DAxEAvi2ie'){
	require_once libfile('class/xml');
	$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/mokuai.xml"));
	foreach ($mokuais  as $k => $v ){
		foreach ($v['version']  as $k1 => $v1 ){
			$mkv[] = $k.'_'.$k1; 
		}
	}
	C::t(GM('server_site')) -> update($indata['siteurl'],array('mokuais'=>implode(',',$mkv)));
}
foreach (explode(',',$site_info['mokuais'])  as $k => $v ){
	list($mokuai,$version) = explode('_',$v);
	$pages_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$version.'/Controler';
	if ($handle = opendir($pages_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				if (filemtime($pages_dir."/".$file) > $indata['mu']){
					$outdata[md5($indata['sitekey'].$mokuai.'_'.substr($file,0,-4))] = convert_uuencode(file_get_contents($pages_dir."/".$file));
				}
			}
		}
	}
}

?>