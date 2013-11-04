<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function getlashoucity(){
	$citypage = 'http://www.lashou.com/changecity';
	$citypage_text = file_get_contents($citypage);
	$ca0 = explode('<div class="citys-list">',$citypage_text);
	$ca01 = explode('<!-- main end -->',$ca0[1]);

	$ca1 = explode('<dd>',$ca01[0]);
	foreach($ca1 as $k=>$v ){
		$ca2 = explode('</dd>',$v);
		$ca3 = explode('<a href="',$ca2[0]);
		foreach($ca3 as $k1=>$v1 ){
			$ca4 = explode('>',$v1);
			$ca5 = explode('"',$ca4[0]);
			if(substr($ca5[0],-11) == '.lashou.com'){
				$ca6[] = array('url'=>$ca5[0],'city'=>str_replace('</a','',$ca4[1]));
			}
		}
	}
	return $ca6;
}

function getlashoupage($url){

	$goodslist_text = file_get_contents($url);
	$ga0 = explode('<div class="content-list">',$goodslist_text);
	$ga1 = explode('<!-- main end -->',$ga0[1]);
	$ga2 = explode('<a class="pagedown"',$ga1[0]);
	$ga3 = explode('>',$ga2[0]);
	return str_replace('</a','',$ga3[count($ga3)-2]);
}

function getdeallink($url){
	$goodslist_text = file_get_contents($url);
	$ga0 = explode('<div class="content-list">',$goodslist_text);
	$ga1 = explode('<!-- main end -->',$ga0[1]);
	$ga2 = explode('<div class="com-img">',$ga1[0]);
	foreach($ga2 as $k=>$v ){
		$ga3 = explode('<a href="http://anqing.lashou.com/deal/',$v);
		$ga4 = explode('.html',$ga3[1]);
		if($ga4[0]){
			$ga5[] =  $ga4[0];
		}

	}
	return $ga5;
}
?>