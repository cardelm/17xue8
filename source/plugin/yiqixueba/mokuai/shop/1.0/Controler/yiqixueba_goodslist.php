<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sid = getgpc('sid');
$subsid = getgpc('subsid');
$navtitle = lang('plugin/yiqixueba','shop');
require_once GC('shop_yiqixueba_shophead');
//参考网站http://www.pailezu.com/

$goods = C::t(GM('shop_goods'))->range();

include template(GT('shop_goodslist'));
?>