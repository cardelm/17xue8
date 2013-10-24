<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$base_page = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage');

if(file_exists($base_page) && is_file($base_page)){
	require_once $base_page;
}
$ajaxtype = trim(getgpc('ajaxtype'));

$ajaxdata = 'ygjhgj';
if($win){
	require_once GC('main_winajax');
}else{
	require_once GC('main_ajax');
}
?>