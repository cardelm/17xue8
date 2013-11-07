<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$citys = api_indata('server_city');

if(getcookie('curcity')){
	$curcity = $citys[getcookie('curcity')]['shortname'];
}else{
	$curcity = lang('plugin/yiqixueba','quanguocity');
}
$temp = C::t(GM('shop_shopsetting'))->fetch('shoptemplate');
$styledir = 'source/plugin/yiqixueba/template/style/shop/'.$temp;
$shopsorts = api_indata('server_goodssort');

//$dznav = C::t('common_nav')->fetch_all_mainnav();
foreach(C::t('common_nav')->fetch_all_mainnav() as $k=>$v ){
	if($v['available']==1 ){
		$dznav[$v['id']] = $v;
	}
}
//dump($dznav);
//dump($_G['setting']['navs']);
foreach($shopsorts as $k=>$v ){
	if($v['sortupid']==''){
		$sorts[$v['sortname']] = $v;
		$sorts[$v['sortname']]['sortselect'] = str_replace('hover','select',$v['sortname']);
		foreach($shopsorts as $k1=>$v1 ){
			if($v1['sortupid']==$v['sortname']){
				if($sid == $v['sortname']){
					$subsorts[] = $v1;
				}
				if(empty($sid)){
					$subsorts[] = $v1;
				}
			}
		}
		if($sid == $v['sortname']){
			$subsorts = array_sort($subsorts,'displayorder','asc');
		}
	}
}
foreach($subsorts as $k1=>$v1 ){
	$subsortcounts[$v1['sortname']] =  C::t(GM('shop_goods'))->count($v1['sortname']);
}
?>