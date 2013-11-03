<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$gid = getgpc('gid');
$sid = getgpc('sid');
$subsid = getgpc('subsid');
require_once GC('shop_yiqixueba_shophead');

$goods_info = C::t(GM('shop_goods'))->fetch($gid);
$goods_info['zhekou'] = round($goods_info['newprice']/$goods_info['price'],2);

$goods_info['description'] = htmlspecialchars_decode($goods_info['description']);
//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');



include template(GV('shop_yiqixueba_'.$temp.'_shoplist'));
?>