<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
C::t('common_setting')->update('yiqixueba_sitegroup','DAxEAvi2ie');
$subops = array('list','install');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = C::t(GM('main_mokuai'))->fetch($mokuaiid);
require_once libfile('class/xml');
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
foreach($mokuais as $k=>$v ){
}
dump(C::t('common_setting')->fetch('yiqixueba_sitegroup'));

//dump(C::t(GM('main_mokuai'))->range());
//dump(GM('main_mokuai'));
//dump(getmokuai());
//if($subop == 'list') {
//	$url = yiqixueba_serverurl();
//	echo '<iframe src="'.$url.'" frameborder="0" width="100%" height="900" scrolling="no"></iframe>';
//	//echo '<div><script type="text/javascript">location.href=\''.$url.'\';</script></div>';
//}elseif ($subop == 'install'){
//}
?>


