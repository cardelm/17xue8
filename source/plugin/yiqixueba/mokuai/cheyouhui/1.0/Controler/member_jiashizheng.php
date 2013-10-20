<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$this_page = 'plugin.php?'.$_SERVER['QUERY_STRING'];

$subops = array('jiashizhenglist','jiashizhengedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


$subtpl = GV('cheyouhui_jiashizheng');

?>