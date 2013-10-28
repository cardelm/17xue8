<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

require_once libfile('class/xml');

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

dump($mod_file);
if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','shopmodel_list_tips'));
		showformheader($this_page.'&subop=shopmodellist');
		showtableheader(lang('plugin/yiqixueba','shopmodel_list').$shopmodels_link);
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','shopmodelname'),lang('plugin/yiqixueba','shopmodeltitle'),lang('plugin/yiqixueba','status')));
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr" >'.lang('plugin/'.$plugin['identifier'],'add_shopmodel').'</a></div></td></tr>';
		echo '<tr><td colspan="1"></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_shopmodel').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newtitle[]" value="" size="15" type="text" class="txt">'],[3, '']],
	];
</script>
EOT;
	}else{
	}
}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
	}else{
	}
}
?>