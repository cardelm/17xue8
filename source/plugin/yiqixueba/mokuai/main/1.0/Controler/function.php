<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


//
function require_cache($filename){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	$page_file = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.md5($sitekey.$filename);
	$runtime_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.md5($sitekey.md5($sitekey.$filename).filemtime($page_file));
	if(file_exists($runtime_file)){
		require_once $runtime_file;
	}else{
		return false;
	}
}//end func

//
function yiqixueba_table($tablename){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	return md5($sitekey.$tablename);
}//end func
//
function yiqixueba_template($templatename){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	return 't'.md5($sitekey.$templatename);
}//end func
?>