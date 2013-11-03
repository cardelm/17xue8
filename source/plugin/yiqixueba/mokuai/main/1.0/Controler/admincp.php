<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$submod = getgpc('submod');
$subop = getgpc('subop');
$admin_menu = array();

$menus_admincp = getadmincpmenus(2);

$menuk = 0;
foreach($menus_admincp as $mk=>$row ){
	$sub_menu = $row['submenu'];
	list($mm,$mt,$mn) = explode("_",$subrow['modfile']);
	$menukk = 0;
	$submenus = array();
	$cursubtitle = $curmokuai = '';
	foreach($sub_menu as $kk => $subrow ){

		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $mk.'_'.$kk;
		}
		if($submod == $mk.'_'.$kk){
			$curtitle = $row['title'];
			$cursubtitle = $subrow['title'];
			$curmokuai = $mk;
			$mod_file = $subrow['modfile'];
		}
		$submenus[] = array($subrow['title'],'plugins&identifier=yiqixueba&pmod=admincp&submod='.$mk.'_'.$kk, $mod_file == $subrow['modfile']);
		$menukk++;
	}
	$admin_menu[] = array(array('menu'=> $cursubtitle ? $cursubtitle : $row['title'],'submenu'=>$submenus),$curmokuai == $mk);
	$menuk++;
}

echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'].' ('.$curtitle.')',$admin_menu,'<span style="float:right;padding-right:40px;"><a href="plugin.php?id='.$plugin['identifier'].'&submod=main_mokuai" target="_blank" class="bold" >'.$plugin['name'].'</a>&nbsp;&nbsp;<a href="plugin.php?id='.$plugin['identifier'].':member"  target="_blank" class="bold" >'.lang('plugin/yiqixueba','member').'</a></span>');

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;


require_once GC($mod_file);
?>