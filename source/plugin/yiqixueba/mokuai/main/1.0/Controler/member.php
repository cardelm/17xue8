<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$submod = getgpc('submod');
$subop = getgpc('subop');

if(!$_G['uid']) {
	showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
}
$navtitle = lang('plugin/yiqixueba','member');

if(getgpc('login_yiqixueba_member') && getgpc('cppwd') && submitcheck('loginsubmit')) {
	$referer = dreferer();
	require_once libfile('function/member');
	loaducenter();
	$result = userlogin($_G['username'], trim(getgpc('cppwd')),'','','',$_G['clientip']);

	if($result['status']){
		dsetcookie('yiqixueba_login','yes',900);
	}
	dheader("Location: plugin.php?id=yiqixueba:member&submod=".$submod);
}
if($submod == 'logout'){
	dsetcookie('yiqixueba_login',null,0);
	$subtpl = GV('main_member_login');
}

if(!getcookie('yiqixueba_login') ){
	$subtpl = GV('main_member_login');
	include template(GV('main_member'));
	exit();
}else{
	dsetcookie('yiqixueba_login','yes',900);
}



$navs = C::t(GM('main_menus'))->fetch_all('member',0);
$subnavs = array();
$menuk = 0;
foreach($navs as $mk=>$row ){
	$sub_menu = C::t(GM('main_menus'))->fetch_all('member',$row['menuid']);
	$menukk = 0;
	foreach($sub_menu as $kk => $subrow ){
		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $row['name'].'_'.$subrow['name'];
		}
		list($m,$p) = explode("_",$submod);
		if($m == $row['name']&& $menukk == 0 ){
			$subnavs[] = $subrow;
		}
		if($submod == $row['name'].'_'.$subrow['name'] ){
			require_once GC($subrow['modfile']);
		}
	}
}
include template(GV('main_member'));
?>