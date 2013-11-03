<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$gid = getgpc('gid');

$goods_info = C::t(GM('shop_goods'))->fetch($gid);
$goods_info['zhekou'] = round($goods_info['newprice']/$goods_info['price'],2);

$goods_info['description'] = htmlspecialchars_decode($goods_info['description']);
$sid = getgpc('sid');
$subsid = getgpc('subsid');
//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');

$shopsorts = C::t(GM('shop_shopsort'))->range();

$goods = C::t(GM('shop_goods'))->range();
foreach($shopsorts as $k=>$v ){
	if($v['sortupid']==0){
		$sorts[$v['shopsortid']] = $v;
		$sorts[$v['shopsortid']]['sortselect'] = str_replace('hover','select',$v['sortname']);
		foreach($shopsorts as $k1=>$v1 ){
			if($v1['sortupid']==$v['shopsortid']){
				if($sid == $v['shopsortid']){
					$subsorts[] = $v1;
				}
				if(empty($sid)){
					$subsorts[] = $v1;
				}
			}
		}
		if($sid == $v['shopsortid']){
			$subsorts = array_sort($subsorts,'displayorder','asc');
		}
	}
}

//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');

$temp = 'default';
$styledir = 'source/plugin/yiqixueba/template/style/shop/'.$temp;
include template(GV('shop_yiqixueba_'.$temp.'_shoplist'));
?>