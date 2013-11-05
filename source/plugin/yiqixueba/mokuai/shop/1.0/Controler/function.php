<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function getlashoucity($updatetype = false){
	global $_G;
	require_once libfile('class/xml');
	$cityxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_citys.xml';
	$citys = xml2array(file_get_contents($cityxmlfile));
	if(!file_exists($cityxmlfile) || $updatetype){
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
					$ca6[str_replace('.lashou.com','',str_replace('http://','',$ca5[0]))] = array('url'=>$ca5[0],'city'=>str_replace('</a','',$ca4[1]));
				}
			}
		}
		file_put_contents ($cityxmlfile,diconv(array2xml($ca6, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}
	return $citys;
}

function getlashoupage($city,$updatetype = false){
	global $_G;
	require_once libfile('class/xml');
	$cityxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_citys.xml';
	$citys = xml2array(file_get_contents($cityxmlfile));
	if(!$citys[$city]['pages'] || $updatetype){
		$goodslist_text = file_get_contents('http://'.$city.'.lashou.com');
		$ga0 = explode('<div class="content-list">',$goodslist_text);
		$ga1 = explode('<!-- main end -->',$ga0[1]);
		$ga2 = explode('<a class="pagedown"',$ga1[0]);
		$ga3 = explode('>',$ga2[0]);
		$citys[$city]['pages'] = str_replace('</a','',$ga3[count($ga3)-2]);
		file_put_contents ($cityxmlfile,diconv(array2xml($citys, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}
	return $citys[$city]['pages'];
}

function getdeallink($city,$updatetype = false){
	global $_G;
	require_once libfile('class/xml');
	$goodsxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'.xml';
	$goodss = xml2array(file_get_contents($goodsxmlfile));
	if(!file_exists($goodsxmlfile) || $updatetype){
		for($i=1;$i<getlashoupage($city)+1 ;$i++ ){
			$goodss = getpagelink($city,$i,$updatetype);
		}
		file_put_contents ($goodsxmlfile,diconv(array2xml($goodss, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}
	return $goodss;
}
function getpagelink($city,$page,$updatetype = false){
	global $_G;
	require_once libfile('class/xml');
	$goodsxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'.xml';
	$goodss = xml2array(file_get_contents($goodsxmlfile));
	$goodslist_text = file_get_contents('http://'.$city.'.lashou.com/page'.$page);
	$ga0 = explode('<div class="content-list">',$goodslist_text);
	$ga1 = explode('<!-- main end -->',$ga0[1]);
	$ga2 = explode('<div class="com-img">',$ga1[0]);
	foreach($ga2 as $k=>$v ){
		$ga3 = explode('.lashou.com/deal/',$v);
		$ga4 = explode('.html',$ga3[1]);
		if($ga4[0]){
			$goodss[$ga4[0]]['oldid'] = $ga4[0];
		}
	}
	file_put_contents ($goodsxmlfile,diconv(array2xml($goodss, 1),"UTF-8", $_G['charset']."//IGNORE"));
	return $goodss;
}
function getgoodscomment($gid){
	$url = 'http://anqing.lashou.com/deal/'.$gid.'.html';
	$goodslist_text = file_get_contents($url);
	$gp0 = explode('<div id="main">',$goodslist_text);
	$gp1 = explode('<div class="sort" id="offline_sort" style="display:none">',$gp0[1]);
	preg_match_all('|<a href=".*</a>|',$gp1[0],$rsT);
	foreach($rsT as $k1=>$v1 ){
		foreach($v1 as $k2=>$v2 ){
			$gp2 = explode('title="',$v2);
			$gp3 = trim(str_replace('"','',str_replace('<a href="','',$gp2[0])));
			$gp4 = explode('">',trim($gp2[1]));
			$data['fenlei'][] = array('name'=>$gp3,'title'=>trim($gp4[0]));

		}
	}
	$gp5 = explode('<span class="right">',$gp1[0]);
	$gp6 = explode('</span',$gp5[1]);
	$data['title'] = $gp6[0];

	$gp7 = explode('<div class="deal-intro">',$gp1[1]);
	$gp8 = explode('<div class="deal-content">',$gp7[1]);
	$data['subtitle'] = trim($gp8[0]);
	$gp9 = explode('<p class="deal-price"><span>&yen;</span>',$gp8[1]);
	$gp10 = explode('</p>',$gp9[1]);
	$data['price'] = $gp10[0];
	$gp11 = explode('<del class="left">&yen;',$gp10[1]);
	$gp12 = explode('</del>',$gp11[1]);
	$data['oldprice'] = $gp12[0];
	$gp13 = explode('<div class="deal-time" time="',$gp8[1]);
	$gp14 = explode('" id="time_over">',$gp13[1]);
	$data['deal-time'] = time()+intval($gp14[0]);
	$gp15 = explode('<ul class="com_adr ">',$gp8[1]);
	$gp16 = explode('</ul>',$gp15[1]);
	$gp17 = explode('=',$gp16[0]);
	foreach($gp17 as $k2=>$v2 ){
		if(substr($v2,0,7) == '"/shop/'){
			$data['shoplink'] = str_replace('.html" class','',str_replace('"/shop/','',$v2));

		}elseif(substr($v2,0,7) == '"link">'){
			$data['shopname'] = substr($v2,7,stripos($v2,'</a>')-7);
		}elseif(substr($v2,0,19) == '"showmapwindow(\'0\','){
			$gp18 = explode(',',$v2);
			$data['shopditu']['x'] = str_replace("'",'',$gp18[1]);
			$data['shopditu']['y'] = str_replace("'",'',$gp18[2]);
		}elseif(substr($v2,0,18) == '"storefront-info">'){
			$gp23 = explode('<span>',$v2);
			$gp24 = explode('</span>',$gp23[1]);
			$data['shopdizhi'] = $gp24[0];
		}
	}
	$gp21 = explode('<div class="commodity-show"><img src="',$gp8[1]);
	$gp22 = explode('" width="',$gp21[1]);
	$data['img'] = $gp22[0];
	$gp19 = explode('<div class="prodetail" id="deal_lazyload">',$gp8[1]);
	if(stripos($gp19[1],'<div id="detail-tag04" class="detail-centit">')){
		$gp20 = explode('<div id="detail-tag04" class="detail-centit">',$gp19[1]);
	}else{
		$gp20 = explode('<div id="detail-tag06" class="detail-buy">',$gp19[1]);
	}
	$data['comment'] = trim($gp20[0]);
	return $data;
}
?>