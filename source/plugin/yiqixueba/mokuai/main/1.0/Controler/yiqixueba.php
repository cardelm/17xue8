<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$submod = getgpc('submod');
$subop = getgpc('subop');

$this_page = str_replace('id=yiqixueba:yiqixueba','id=yiqixueba',$_SERVER['QUERY_STRING']);
$this_page = substr($_SERVER['QUERY_STRING'],12,strlen($_SERVER['QUERY_STRING'])-12);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

list($m,$n) = explode('_',$submod);

require_once GC($m.'_yiqixueba_'.$n);

?>