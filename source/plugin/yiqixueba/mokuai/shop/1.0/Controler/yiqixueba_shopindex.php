<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
dump($this_page);
$sid = getgpc('sid');
$subsid = getgpc('subsid');
//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');

$shopsorts = C::t(GM('shop_shopsort'))->range();


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

//dump($subsorts);
$temp = 'default';
$styledir = 'source/plugin/yiqixueba/template/style/shop/'.$temp;
include template(GV('shop_yiqixueba_'.$temp.'_shopindex'));
?>