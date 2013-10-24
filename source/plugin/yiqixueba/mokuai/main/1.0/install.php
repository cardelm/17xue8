<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$githubfile = DISCUZ_ROOT.'source/plugin/yiqixueba/github.func.php';
if($githubfile){
	require_once $githubfile;
}

//$server_siteurl = 'http://localhost/yiqixueba/dz3utf8/';
//测试阶段
$server_siteurl = $_G['siteurl'];

$installdata = array();
require_once DISCUZ_ROOT.'/source/discuz_version.php';
$installdata['charset'] = $_G['charset'];
$installdata['clientip'] = $_G['clientip'];
$installdata['siteurl'] = $_G['siteurl'];
$installdata['version'] = DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'-'.DISCUZ_FIXBUG;
$installdata = serialize($installdata);
$installdata = base64_encode($installdata);
$api_url = $server_siteurl.'plugin.php?id=yiqixueba:api&apiaction=main_install&indata='.$installdata.'&sign='.md5(md5($installdata));
$xml = @file_get_contents($api_url);
require_once libfile('class/xml');
$outdata = is_array(xml2array($xml)) ? xml2array($xml) : $xml;

?>