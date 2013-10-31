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

$this_page = 'plugin.php?'.$_SERVER['QUERY_STRING'];
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$navs = getmembernav(2);
$menuk = 0;
foreach($navs as $mk=>$row ){
	$menukk = 0;
	foreach($row['submenu'] as $kk => $subrow ){
		if ( $menuk == 0 && $menukk == 0 && empty($submod) ){
			$submod = $mk.'_'.$kk;
		}
		list($m,$p) = explode("_",$submod);
		if( $m==$mk && !$p && $menukk == 0){
			$submod = $m.'_'.$kk;
			$p = $kk;
		}
		if( $m == $mk){
			$subnavs[$kk] = $subrow;
		}
		if($submod == $mk.'_'.$kk ){
			require_once GC($subrow['modfile']);
		}
		$menukk++;
	}
	$menuk++;
}

include template(GV('main_member'));
?>