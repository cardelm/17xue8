<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//参考网站http://www.pailezu.com/
$navtitle = lang('plugin/yiiqxueba','shop');

$temp = 'default';
$styledir = 'source/plugin/yiqixueba/template/style/shop/'.$temp;
include template(GV('shop_yiqixueba_'.$temp.'_shoplist'));
?>