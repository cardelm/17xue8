<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sid = getgpc('sid');
$subsid = getgpc('subsid');
require_once GC('shop_yiqixueba_shophead');
//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');




$goods = C::t(GM('shop_goods'))->range();

//dump($shopsorts);
include template(GV('shop_yiqixueba_'.$temp.'_shopindex'));
?>