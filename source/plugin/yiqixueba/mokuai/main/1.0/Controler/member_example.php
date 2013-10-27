<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$this_page = 'plugin.php?'.$_SERVER['QUERY_STRING'];
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('list','edit','del');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

if($subop == 'list') {
}elseif($subop == 'edit') {
}elseif($subop == 'del') {
}
$subtpl = GV('cheyouhui_member_field');
?>