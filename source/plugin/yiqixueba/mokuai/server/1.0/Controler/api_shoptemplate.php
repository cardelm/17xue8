<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/sitegroups.xml"));
$site_info = C::t(GM('server_site'))->fetch($indata['siteurl']);
if($site_info['sitegroup'] == 'DAxEAvi2ie' ){
	$shoptemps = getshoptemp();
	foreach($shoptemps as $k=>$v ){
		$outdata[] = $v[0];
	}
}else{
	$outdata = $sitegroups[$site_info['sitegroup']]['shoptemp'];
}
?>