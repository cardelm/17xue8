<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
//
function GC($pagename){
	global $sitekey;
	$page_filename = md5($sitekey.$pagename);
	$page_file = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.$page_filename;
	$cache_filename = md5($sitekey.$page_filename.filemtime($page_file));
	$cache_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.$cache_filename;
	return $cache_file;
}//

//dump(file_exists(GC('main_function')));
//dump((DISCUZ_ROOT.'source/plugin/yiqixueba/table/table_'.GM('main_mokuai')));
//dump(file_exists(DISCUZ_ROOT.'source/plugin/yiqixueba/table/table_'.GM('main_mokuai').'.php'));
//dump(GM('main_mokuai'));
//dump(C::t(GM('main_mokuai'))->fetch_all());
//
function GM($tablename){
	global $sitekey;
	$table_file = '#yiqixueba#y_'.md5($sitekey.$tablename);
	return $table_file;
}//
//
function GV($templatename){
	global $sitekey;
	$template_file = 'yiqixueba:'.md5($sitekey.$templatename);
	return $template_file;
}//

$mokuais = array('main'=>'1.0','server'=>'1.0','shop'=>'1.0','wxq123'=>'1.0','yqxb'=>'1.0','cheyouhui'=>'1.0');
foreach($mokuais as $k=>$v ){
	if(file_exists(GC($k.'_function'))){
		require_once GC($k.'_function');
	}
}
?>