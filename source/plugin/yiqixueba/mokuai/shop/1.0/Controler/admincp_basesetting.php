<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$shopsetting =  C::t(GM('shop_shopsetting'))->range();
$shoptemplates = api_indata('server_shoptemplate');
foreach($shoptemplates as $k=>$v ){
	$shoptempradio[] = array($v,lang('plugin/yiqixueba','shoptemp_'.$v));
}

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','edit_basesetting_tips'));
	showformheader($this_page.'&subop=edit','enctype');
	showtableheader(lang('plugin/yiqixueba','basesetting_option'));
	showsetting(lang('plugin/yiqixueba','shoptemplate'),array('shopsetting[shoptemplate]',$shoptempradio),$shopsetting['shoptemplate']['svalue'],'select','',0,lang('plugin/yiqixueba','basesettingstatus_comment'),'','',true);//radio

	showsubmit('submit');
	showtablefooter();
	showformfooter();
} else {
	foreach($_POST['shopsetting'] as $k=>$v ){
		$data = array('skey'=>$k,'svalue'=>$v);
		if(!C::t(GM('shop_shopsetting'))->skey_exists($k)){
			C::t(GM('shop_shopsetting'))->insert($data);
		}else{
			C::t(GM('shop_shopsetting'))->update($k,$data);
		}
	}

	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','edit_basesetting_succeed'), 'action='.$this_page.'&subop=basesettinglist', 'succeed');
}

?>