<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$submod = getgpc('submod');
$subop = getgpc('subop');

$this_page = str_replace('id=yiqixueba:yiqixueba','id=yiqixueba',$_SERVER['QUERY_STRING']);
$this_page = substr($_SERVER['QUERY_STRING'],12,strlen($_SERVER['QUERY_STRING'])-12);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$submod = $submod ? $submod : 'main_index';

list($m,$n) = explode("_",$submod);

$submod_file = $m.'_yiqixueba_'.$n;

require_once GC($submod_file);

if($submod == 'shop_baidumap'){
	include template(GV('shop_yiqixueba_baidumap'));
}

?>