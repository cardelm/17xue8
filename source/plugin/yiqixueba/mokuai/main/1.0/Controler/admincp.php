<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$submod = getgpc('submod');
$subop = getgpc('subop');
$admin_menu = array();

$menus_admincp = C::t(GM('main_menus'))->fetch_all('admincp',0);

$menuk = 0;

foreach($menus_admincp as $mk=>$row ){
	$sub_menu = C::t(GM('main_menus'))->fetch_all('admincp',$row['menuid']);
	$menukk = 0;
	$submenus = array();
	foreach($sub_menu as $kk => $subrow ){
		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $row['name'].'_'.$subrow['name'];
		}
		list($m,$p) = explode("_",$submod);
		$submenus[] = array($subrow['title'],'plugins&identifier=yiqixueba&pmod=admincp&submod='.$row['name'].'_'.$subrow['name'],$submod == $row['name'].'_'.$subrow['name']);
		if($submod == $row['name'].'_'.$subrow['name']){
			$mod_file =  $subrow['modfile'];
			$curtitle = $subrow['title'];
			$curmokuai = $row['title'];
		}
	}
	$admin_menu[] = array(array('menu'=>$submod == $row['name'].'_'.$p ? $curtitle : $row['title'],'submenu'=>$submenus),$m == $row['name']);
}

echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'].' ('.$curmokuai.')',$admin_menu,'<span style="float:right;padding-right:40px;"><a href="plugin.php?id='.$plugin['identifier'].'" target="_blank" class="bold" >'.$plugin['name'].'</a>&nbsp;&nbsp;<a href="plugin.php?id='.$plugin['identifier'].':member"  target="_blank" class="bold" >'.lang('plugin/yiqixueba','member').'</a></span>');

require_once GC($mod_file);
?>