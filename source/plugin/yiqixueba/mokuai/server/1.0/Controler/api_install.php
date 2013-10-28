<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$site_info = C::t(GM('server_site'))->fetch($indata['siteurl']);

if(!$site_info){
	$data = array();
	$data['salt'] = random(6);
	$data['charset'] = $indata['charset'];
	$data['clientip'] = $indata['clientip'];
	$data['version'] = $indata['version'];
	$data['siteurl'] = $indata['siteurl'];
	$data['sitekey'] = md5($indata['siteurl'].$data['salt']);
	$data['sitegroup'] = $indata['sitegroup'];
	$data['installtime'] = time();
	C::t(GM('server_site'))->insert($data);
}
$outdata = $site_info;
?>