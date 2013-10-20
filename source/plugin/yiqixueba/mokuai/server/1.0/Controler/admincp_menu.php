<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('menulist','menuedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];
$menus_type = getgpc('menus_type');

$menus_types = array('admincp','member','yiqixueba');
$menus_type = in_array($menus_type,$menus_types) ? $menus_type : $menus_types[0];


if($subop == 'menulist') {
	if(!submitcheck('submit')) {
		$menus_link = '';
		foreach ($menus_types as $k => $v ){
			$menus_link .= '&nbsp;&nbsp;';
			if($menus_type == $v){
				$menus_link .= lang('plugin/yiqixueba','menutype_'.$v);
			}else{
				$menus_link .= '<a href = "'.ADMINSCRIPT.'?action='.$this_page.'&menus_type='.$v.'">'.lang('plugin/yiqixueba','menutype_'.$v).'</a>';
			}
		}
		showtips(lang('plugin/yiqixueba','menu_list_tips').lang('plugin/yiqixueba','menutype_'.$menus_type).'</li>');
		showformheader($this_page.'&subop=menulist');
		showtableheader(lang('plugin/yiqixueba','menu_list').$menus_link);
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','menu_name'),lang('plugin/yiqixueba','menu_title'),lang('plugin/yiqixueba','modfile'),lang('plugin/yiqixueba','status')));
		showtagheader('tbody', '', true);
		
		$adminmenus = getmenus($menus_type);
		$menus_admincp = C::t(GM('main_menus'))->fetch_all($menus_type,0,'server');
		foreach($menus_admincp as $mk=>$row ){
			$sub_menu = C::t(GM('main_menus'))->fetch_all($menus_type,$row['menuid'],'server');
			
			showtablerow('', array('class="td25"', 'class="td25"', '', '','','class="td25"'), array(

				(count($sub_menu) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$row['menuid'].'\', this)">[+]</a>' : '').(count($sub_menu) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[menuid]\" />"),

				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$row[menuid]]\" value=\"$row[displayorder]\">",

				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[menuid]]\" value=\"".$row['name']."\"$readonly>"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, $row[menuid])\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_submenu')."</a></div>",

				"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[menuid]]\" value=\"".($row['title'])."\">",

				'<input type="hidden" name="upidnew['.$row['menuid'].']" value="0" />',

				"<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$row[menuid]]\" value=\"1\" ".($row['status'] ? ' checked="checked"' : '')." />",
			));
			showtagheader('tbody', 'subnav_'.$row['menuid'], false);
			foreach ($sub_menu  as $kk => $subrow ){
				$modlist = '<select name="modfilenew['.$subrow['menuid'].']"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
				foreach ( $adminmenus as $k => $v ){
					foreach ($v  as $k1 => $v1 ){
						$modlist .= '<option value="'.$k.'_'.$menus_type.'_'.$v1.'" '.($subrow['modfile'] == $k.'_'.$menus_type.'_'.$v1 ? ' selected':'' ).'>'.$k.'_'.$v1.'</option>';
					}

				}
				$modlist .= '</select>';
				showtablerow('', array('class="td25"', 'class="td25"', '', '', '','class="td25"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$subrow[menuid]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$subrow[menuid]]\" value=\"$subrow[displayorder]\">",
					"<div class=\"board\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$subrow[menuid]]\" value=\"".$subrow['name']."\"$readonly></div>",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$subrow[menuid]]\" value=\"".$subrow['title']."\">",
					$modlist.'<input type="hidden" name="upidnew['.$subrow['menuid'].']" value="'.$row['menuid'].'" />',
					"<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$subrow[menuid]]\" value=\"1\" ".($subrow['status'] ? ' checked="checked"' : '')." />",
					));
			}
			
			showtagfooter('tbody');
		}
		showtagfooter('tbody');
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_menu').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		$modlist = '<select name="newmodfile[]"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
		foreach ( $adminmenus as $k => $v ){
			foreach ($v  as $k1 => $v1 ){
				$modlist .= '<option value="'.$k.'_'.$menus_type.'_'.$v1.'">'.$k.'_'.$v1.'</option>';
			}
		}
		$modlist .= '</select>';
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newname[]" value="" size="15" type="text" class="txt">'],[1,'<input name="newtitle[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupid[]" value="0" />']],
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<div class=\"board\"><input name="newname[]" value="" size="15" type="text" class="txt"></div>'], [1,'<input name="newtitle[]" value="" size="15" type="text" class="txt">'], [5, '$modlist<input type="hidden" name="newupid[]" value="{1}" />']]
	];
</script>
EOT;
	}else{
		if($ids = dimplode($_GET['delete'])) {
			C::t(GM('main_menus'))->delete_by_menuid($menus_type, $_GET['delete']);
		}

		if(is_array($_GET['namenew'])) {
			foreach($_GET['namenew'] as $id => $name) {
				$name = trim(dhtmlspecialchars($name));
				$titlenew = trim(dhtmlspecialchars($_GET['titlenew'][$id]));
				$displayordernew = intval($_GET['displayordernew'][$id]);
				$upidnew = intval($_GET['upidnew'][$id]);
				$statusnew = intval($_GET['statusnew'][$id]);
				$modfilenew = trim($_GET['modfilenew'][$id]);
				$data = array(
						'displayorder' => $displayordernew,
						'upid' => $upidnew,
						'name' => $name,
						'title' => $titlenew,
						'modfile' => $modfilenew,
						'status' => $statusnew,
					);
				//dump($data);
				if(!empty($titlenew) && !empty($name) && $upidnew == 0 || $upidnew > 0 && !empty($titlenew) && !empty($name) && !empty($modfilenew)) {
					C::t(GM('main_menus'))->update($id, $data);
				}
			}
		}

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
					C::t(GM('main_menus'))->insert($data);
				}
			}
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','menu_edit_succeed'), 'action='.$this_page.'&subop=menulist', 'succeed');
	}
}
?>