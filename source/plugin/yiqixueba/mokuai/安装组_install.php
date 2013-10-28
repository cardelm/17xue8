<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(fsockopen('localhost', 80)){
	$installdata = array();
	require_once DISCUZ_ROOT.'/source/discuz_version.php';
	$installdata['sitegroup'] = 'ViI7m2R9QM';
	$installdata['charset'] = $_G['charset'];
	$installdata['clientip'] = $_G['clientip'];
	$installdata['siteurl'] = $_G['siteurl'];
	$installdata['version'] = DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'-'.DISCUZ_FIXBUG;
	$installdata = serialize($installdata);
	$installdata = base64_encode($installdata);
	$api_url = 'http://localhost/web/17xue8/plugin.php?id=yiqixueba:api&apiaction=server_install&indata='.$installdata.'&sign='.md5(md5($installdata));
	$xml = @file_get_contents($api_url);
	require_once libfile('class/xml');
	$outdata = is_array(xml2array($xml)) ? xml2array($xml) : $xml;
}else{
	exit(lang('plugin/yiqixueba','check_connection'));
}
?>