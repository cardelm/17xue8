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
require_once libfile('class/xml');
$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/sitegroups.xml"));

$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));

$menus = xml2array(file_get_contents(MOKUAI_DIR."/menus.xml"));

$outdata['mokuais'] = $sitegroups[$indata['sitegroup']]['installmokuai'];//从安装文件中的用户组得到安装初始模块
foreach ($sitegroups[$indata['sitegroup']]['nodes']  as $k => $v ){
	list($m,$t,$n) = explode("_",$v);
	if(in_array($m,$sitegroups[$indata['sitegroup']]['installmokuai'])){
		$outdata['nodes'][] = $v;
	}
}
foreach ($sitegroups[$indata['sitegroup']]['menu']  as $k => $v ){
	list($m,$t,$n) = explode("_",$v);
	if(in_array($m,$sitegroups[$indata['sitegroup']]['installmokuai'])){
		$outdata['menu'][] = $v;
	}
}
$outdata['menus'] = $menus;
?>