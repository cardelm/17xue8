<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
$site_info = C::t(GM('server_site'))->fetch_by_siteurl($indata['siteurl']);
$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/sitegroups.xml"));
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/mokuai.xml"));
foreach(dunserialize($site_info['sitegroup']) as $k=>$v ){
	foreach($sitegroups[$v]['upgrademokuai'] as $k1=>$v1 ){
		list($mokuai,$version) = explode('_',$v1);
		$outdata[$mokuai][$version] = $mokuais[$mokuai]['version'][$version];
	}
}
//$outdata = array_unique($outdata);
//$outdata = $mokuais;
?>