<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$base_page = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage');

if(file_exists($base_page) && is_file($base_page)){
	require_once $base_page;
	dump($base_page);
	dump(GC('main_yiqixueba'));
	dump(file_exists(GC('main_yiqixueba')));
	require_once GC('main_yiqixueba');
}
?>