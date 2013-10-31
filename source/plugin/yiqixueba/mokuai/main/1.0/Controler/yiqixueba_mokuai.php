<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$mod = getgpc('mod');
$navs = getyiqixuebanav(2);
$menuk = 0;
foreach($navs as $mk=>$row ){
	$menukk = 0;
	foreach($row['submenu'] as $kk => $subrow ){
		if ( $menuk == 0 && $menukk == 0 && empty($mod) ){
			$mod = $mk.'_'.$kk;
		}
		list($m,$p) = explode("_",$mod);
		if( $m==$mk && !$p && $menukk == 0){
			$mod = $m.'_'.$kk;
			$p = $kk;
		}
		if( $m == $mk){
			$subnavs[$kk] = $subrow;
		}
		if($mod == $mk.'_'.$kk ){
			//require_once GC($subrow['modfile']);
		}
		$menukk++;
	}
	$menuk++;
}

$navtitle = lang('plugin/yiqixueba','mokuai_display');

include template(GV('main_yiqixueba_mokuai'));
?>