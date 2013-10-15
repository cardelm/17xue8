<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$submod = getgpc('submod');
$admin_menu = $submenus = array();

echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'],$admin_menu,'<a href="plugin.php?id='.$plugin['identifier'].'" target="_blank" class="bold" >'.$plugin['name'].'</a>&nbsp;&nbsp;<a href="plugin.php?id='.$plugin['identifier'].':member"  target="_blank" class="bold" >'.lang('plugin/yiqixueba','member').'</a></span>');

dump(yiqixueba_table('main_mokuai'));
?>