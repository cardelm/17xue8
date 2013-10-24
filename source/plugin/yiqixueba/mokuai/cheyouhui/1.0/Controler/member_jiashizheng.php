<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$this_page = 'plugin.php?'.$_SERVER['QUERY_STRING'];

$subops = array('jiashizhenglist','jiashizhengedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

if($subop == 'jiashizhenglist') {
}elseif($subop == 'jiashizhengedit') {
	$fields = C::t(GM('cheyouhui_field'))->fetch_all_by_fieldtype('jsz');
	dump($fields);
}

$subtpl = GV('cheyouhui_jiashizheng');

?>