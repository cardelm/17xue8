<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//dump($pages);
//dump($tables);
//dump($templates);
foreach($tables as $k=>$v ){
	dump($v);
	dump(GM($v));
}
//dump(file_exists(GC('cheyouhui_ajax')));
//dump($mokuais);

$setting = array(
	'sitekey' => C::t('common_setting')->fetch('yiqixueba_siteurlkey'),
	//'updatetime' => time(),
	'salt' => C::t('common_setting')->fetch('yiqixueba_salt'),
	'version' => '1.0',
	'basepage' => C::t('common_setting')->fetch('yiqixueba_basepage'),
	'sitegroup' => 'DAxEAvi2ie',
);
foreach ($setting as $k => $v ){
	if(!C::t(GM('main_setting'))->skey_exists($k)){
		C::t(GM('main_setting'))->insert(array('skey'=>$k,'svalue'=>$v));
	}
}
$sitekey = C::t(GM('main_setting'))->fetch('sitekey');
$server_update = api_indata('checkupdate',array('mu'=>C::t(GM('main_setting'))->fetch('updatetime')));
if($server_update){
	foreach ($server_update  as $k => $v ){
		$page_file = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.$k;
		$cache_filename = md5($sitekey.$k.filemtime($page_file));
		$cache_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.$cache_filename;
		if(file_exists($cache_file)){
			@unlink($cache_file);
		}
		file_put_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.$k,$v);
		$cache_filename = md5($sitekey.$k.filemtime($page_file));
		$cache_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.$cache_filename;
		file_put_contents($cache_file,convert_uudecode(file_get_contents($page_file)));
		if( $k == md5($sitekey.'main_basepage')){
			C::t(GM('main_setting'))->update('basepage',$cache_filename);
		}
		dump($k);
	}
	C::t(GM('main_setting'))-> update('updatetime',array('svalue'=>time()));
}
dump($server_update);
?>