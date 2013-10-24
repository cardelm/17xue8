<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$base_page = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage');

if(file_exists($base_page) && is_file($base_page)){
	require_once $base_page;
}
$ajaxtype = trim(getgpc('ajaxtype'));

foreach($mokuais as $k=>$v ){
	if(file_exists(GC($k.'_ajax'))){
		require_once GC($k.'_ajax');
	}
}

if($win){
	include template(GV('main_winajax'));
}else{
	include template(GV('main_ajax'));
}
?>