<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$base_page = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage');

if(file_exists($base_page) && is_file($base_page)){
	require_once $base_page;
}

$indata = addslashes($_GET['indata']);
$sign = addslashes($_GET['sign']);
$apiaction = addslashes($_GET['apiaction']);

$outdata = array();

if($sign != md5(md5($indata))) {
	$outdata[] = lang('plugin/'.$plugin['identifier'],'api_sign_error');
}
if(!$apiaction) {
	$outdata[] = lang('plugin/'.$plugin['identifier'],'api_apiaction_error');
}


$indata = base64_decode($indata);
$indata = dunserialize($indata);
////////////////////////////////////////

////////////////////////////////////////
if(is_array($outdata)){
	require_once libfile('class/xml');
	$filename = $apiaction.'.xml';
	$plugin_export = array2xml($outdata, 1);
	ob_end_clean();
	dheader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	dheader('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	dheader('Cache-Control: no-cache, must-revalidate');
	dheader('Pragma: no-cache');
	dheader('Content-Encoding: none');
	dheader('Content-Length: '.strlen($plugin_export));
	dheader('Content-Disposition: attachment; filename='.$filename);
	dheader('Content-Type: text/xml');
	echo $plugin_export;
	define('FOOTERDISABLED' , 1);
	exit();
}else{
	echo $outdata;
}

?>