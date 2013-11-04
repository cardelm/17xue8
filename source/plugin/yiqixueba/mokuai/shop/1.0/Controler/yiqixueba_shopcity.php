<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once GC('shop_yiqixueba_shophead');

foreach ( $citys as $k => $v ){
	$citylist[$v['abridge']][] = $v;
}

$cityname = getgpc('cityname');
if($cityname){
	dsetcookie('curcity',$cityname);
	dheader("Location: ./plugin.php?id=yiqixueba&submod=shop_shopindex");
}
$navtitle = lang('plugin/yiiqxueba','shop');

include template(GV('shop_yiqixueba_'.$temp.'_shopcity'));
?>