<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'source/plugin/yiqixueba/function.func.php';
make_page();
require_cache('main_function_admincp');
require_cache('main_admincp');

$mokuainame = 'main';
$mokuaiver = '1.0';
?>