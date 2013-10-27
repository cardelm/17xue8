<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//$pages =dunserialize(C::t('common_setting')->fetch('yiqixueba_pages'));
//$tables =dunserialize(C::t('common_setting')->fetch('yiqixueba_tables'));
//$templates =dunserialize(C::t('common_setting')->fetch('yiqixueba_templates'));
////
function getmenus($menustype = 'admincp'){
	$outmenus = array();
	$pages =dunserialize(C::t('common_setting')->fetch('yiqixueba_pages'));
	foreach($pages as $k=>$v ){
		list($mokuai,$mtype,$mod) = explode("_",$v);
		if($mod && $mtype == $menustype){
			$outmenus[$mokuai][] = $mod;
		}
	}
	return $outmenus;
}


//
function getsitekey(){
}

//
function yiqixueba_serverurl(){
	$url = 'http://localhost/web/17xue8/plugin.php?id=yiqixueba&submod=main_mokuai';
	return $url;
}//end func
?>