<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once DISCUZ_ROOT.'source/plugin/yiqixueba/install.php';
require_once DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage');
require_cache('main_admincp');
?>