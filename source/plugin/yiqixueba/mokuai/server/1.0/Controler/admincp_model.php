<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','shopmodel_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','shopmodel_list'));
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','shopmodelname'),lang('plugin/yiqixueba','shopmodeltitle'),lang('plugin/yiqixueba','status')));
		$models = C::t(GM('server_model'))->fetch_all();
		foreach ($models  as $k => $v ){
			showtablerow('', array('class="td25"', 'class="td25"', '', '','','class="td25"'), array(
				(count($sub_menu) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$row['menuid'].'\', this)">[+]</a>' : '').(count($sub_menu) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[menuid]\" />"),

				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$row[menuid]]\" value=\"$row[displayorder]\">",

				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[menuid]]\" value=\"".$row['name']."\"$readonly>"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, $row[menuid])\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_submenu')."</a></div>",

				"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[menuid]]\" value=\"".($row['title'])."\">",

				'<input type="hidden" name="upidnew['.$row['menuid'].']" value="0" />',

				"<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$row[menuid]]\" value=\"1\" ".($row['status'] ? ' checked="checked"' : '')." />",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/'.$plugin['identifier'],'add_shopmodel').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newname[]" value="" size="15" type="text" class="txt">'],[1,'<input name="newtitle[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupid[]" value="0" />']],
	];
</script>
EOT;
	}else{
		if(is_array($_GET['newname'])) {
			foreach($_GET['newname'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newtitle = trim(dhtmlspecialchars($_GET['newtitle'][$k]));
				$newmodfile = trim(dhtmlspecialchars($_GET['newmodfile'][$k]));
				$newupid = intval($_GET['newupid'][$k]);
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				if(!empty($newtitle) && !empty($v) && $newupid == 0 || $newupid > 0 && !empty($newtitle) && !empty($v) && !empty($newmodfile)) {
					$data = array(
						'displayorder' => $newdisplayorder,
						'upid' => $newupid,
						'name' => $v,
						'title' => $newtitle,
						'modfile' => $newmodfile,
						'type' => $menus_type,
						'status' => 0,
					);
					dump($data);
					//C::t(GM('main_menus'))->insert($data);
				}
			}
		}
	}
}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
	}else{
	}
}
?>