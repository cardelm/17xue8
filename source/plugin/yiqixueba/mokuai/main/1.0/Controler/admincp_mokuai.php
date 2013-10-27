<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','install');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = C::t(GM('main_mokuai'))->fetch_by_mokuaiid($mokuaiid);

if($subop == 'list') {
	$url = yiqixueba_serverurl();
	echo '<iframe src="'.$url.'" frameborder="0" width="100%" height="900" scrolling="no"></iframe>';
	//echo '<div><script type="text/javascript">location.href=\''.$url.'\';</script></div>';
}elseif ($subop == 'install'){
}
?>


