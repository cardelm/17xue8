<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$submod = getgpc('submod');
$subop = getgpc('subop');
$admin_menu = $submenus = array();

$menuk = 0;
foreach(getmenus('admincp') as $k=>$v ){
	$menukk = 0;
	foreach($v as $k1=>$v1 ){
		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $k.'_'.$v1;
		}
		list($m,$p) = explode("_",$submod);
		$current_menu = lang('plugin/yiqixueba',$m.'_admincp_menu_'.$p);
		$submenus[] = array(lang('plugin/yiqixueba',$k.'_'.'admincp_menu_'.$v1),'plugins&identifier=yiqixueba&pmod=admincp&submod='.$k.'_'.$v1,$submod == $k.'_'.$v1);
	}
	$admin_menu[] = array(array('menu'=>$current_menu  ? $current_menu  : lang('plugin/yiqixueba',$k.'_admincp_topmenu'),'submenu'=>$submenus),$m == $k);
}

echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'],$admin_menu,'<span style="float:right;padding-right:40px;"><a href="plugin.php?id='.$plugin['identifier'].'" target="_blank" class="bold" >'.$plugin['name'].'</a>&nbsp;&nbsp;<a href="plugin.php?id='.$plugin['identifier'].':member"  target="_blank" class="bold" >'.lang('plugin/yiqixueba','member').'</a></span>');

require_once require_cache($m.'_admincp_'.$p);

dump($tables);
?>