<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$groups = array('business','dianzhang','caiwu','shouyin','kaxiaoshou','guest');
foreach( $groups as $k=>$v ){
	$groups_t[] = lang('plugin/yiqixueba','g_'.$v);
}

$edit = trim(getgpc('edit'));
$edit_stauts = in_array($edit,$groups);
$dq_edit = lang('plugin/yiqixueba','g_'.$edit);


$subtpl = GV('yikatong_member_base');
?>