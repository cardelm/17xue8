<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$navtitle = lang('plugin/yiiqxueba','shop');
$temp = 'default';
$styledir = 'source/plugin/yiqixueba/template/style/shop/'.$temp;

include template(GV('shop_yiqixueba_shopcity'));
?>