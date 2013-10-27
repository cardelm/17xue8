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
	$cursubtitle = '';
	foreach($sub_menu as $kk => $subrow ){
		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $subrow['name'];
		}
		if($submod == $subrow['name']){
			$curtitle = $row['title'];
			$cursubtitle = $subrow['title'];
			$curmokuai = $row['name'];
			$mod_file =  $subrow['modfile'];
		}
		$submenus[] = array($subrow['title'],'plugins&identifier=yiqixueba&pmod=admincp&submod='.$subrow['name'],$mod_file == $subrow['modfile']);
	}
	$admin_menu[] = array(array('menu'=> $cursubtitle ? $cursubtitle : $row['title'],'submenu'=>$submenus),$curmokuai == $row['name']);
}

echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'].' ('.$curtitle.')',$admin_menu,'<span style="float:right;padding-right:40px;"><a href="plugin.php?id='.$plugin['identifier'].'" target="_blank" class="bold" >'.$plugin['name'].'</a>&nbsp;&nbsp;<a href="plugin.php?id='.$plugin['identifier'].':member"  target="_blank" class="bold" >'.lang('plugin/yiqixueba','member').'</a></span>');

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;


require_once GC($mod_file);
?>