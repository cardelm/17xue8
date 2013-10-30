<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$systemgroups = array('ViI7m2R9QM','QiMzl6m9o6','DAxEAvi2ie');

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

$outdata['mokuais'] = $sitegroups[$indata['sitegroup']]['installmokuai'];//从安装文件中的用户组得到安装初始模块

foreach ($sitegroups[$indata['sitegroup']]['nodes']  as $k => $v ){
	list($m,$t,$n) = explode("_",$v);
	if(in_array($m,$sitegroups[$indata['sitegroup']]['installmokuai'])){
		$outdata['nodes'][] = $v;
	}
}

foreach ($sitegroups[$indata['sitegroup']]['menus']  as $k => $v ){
	foreach($v as $k1=>$v1 ){
		$mk1 = random(10);
		foreach($v1 as $k2=>$v2 ){
			foreach($v2 as $k3=>$v3 ){
				$mk3 = random(10);
				list($m,$t,$n) = explode("_",$v3['modfile']);
				if(in_array($m,$sitegroups[$indata['sitegroup']]['installmokuai'])){
					$outdata['menus'][$k][$mk1]['title'] = $v1['title'];
					$outdata['menus'][$k][$mk1]['displayorder'] = $v1['displayorder'];
					$outdata['menus'][$k][$mk1]['submenu'][$mk3]['title'] = $v3['title'];
					$outdata['menus'][$k][$mk1]['submenu'][$mk3]['displayorder'] = $v3['displayorder'];
					$outdata['menus'][$k][$mk1]['submenu'][$mk3]['modfile'] = $v3['modfile'];
				}
			}
		}
	}
}

?>