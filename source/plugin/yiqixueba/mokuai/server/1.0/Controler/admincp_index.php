<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
foreach ( getfilename('shop/1.0/Data') as $k => $v ){
	//dump(MOKUAI_DIR.'/shop/1.0/Data/'.$v.".xml");
	$data = xml2array(file_get_contents(MOKUAI_DIR.'/shop/1.0/Data/'.$v.".xml"));
	foreach ($data['Data'] as $k1 => $v1 ){
		unset($v1['template']);
		unset($v1['optionid']);
		unset($v1['classid']);
		unset($v1['typeid']);
		unset($v1['optionid']);
		unset($v1['optionid']);
		dump($v1);
	}
	//dump($data['Data']);
}
?>