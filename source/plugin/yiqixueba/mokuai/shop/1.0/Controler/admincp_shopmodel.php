<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('shopmodellist','shopmodeledit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


if($subop == 'shopmodellist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','shopmodel_list_tips'));
		showformheader($this_page.'&subop=shopmodellist');
		showtableheader(lang('plugin/yiqixueba','shopmodel_list').$shopmodels_link);
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','shopmodel_name'),lang('plugin/yiqixueba','shopmodel_title'),lang('plugin/yiqixueba','modfile'),lang('plugin/yiqixueba','status')));
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_shopmodel').'</a></div></td></tr>';
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr" >'.lang('plugin/'.$plugin['identifier'],'add_mokuai').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subop == 'shopmodeledit') {
	if(!submitcheck('submit')) {
	}else{
	}
}
?>