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

require_once libfile('class/xml');
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
$mokuais = array_sort($mokuais,'displayorder','desc');

$menus = xml2array(file_get_contents(MOKUAI_DIR."/menus.xml"));
$menus[$menus_type] = array_sort($menus[$menus_type],'displayorder');


if($subop == 'menulist') {
	if(!submitcheck('submit')) {
		$menus_link = '';
		foreach ($menus_types as $k => $v ){
			$menus_link .= '&nbsp;&nbsp;';
			if($menus_type == $v){
				$menus_link .= '<span style="color:#000000;">'.lang('plugin/yiqixueba','menutype_'.$v).'</span>';
			}else{
				$menus_link .= '<a href = "'.ADMINSCRIPT.'?action='.$this_page.'&menus_type='.$v.'">'.lang('plugin/yiqixueba','menutype_'.$v).'</a>';
			}
		}
		showtips(lang('plugin/yiqixueba','menu_list_tips'));
		showformheader($this_page.'&subop=menulist');
		showtableheader(lang('plugin/yiqixueba','menu_list').$menus_link);
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','menu_title'),lang('plugin/yiqixueba','modfile'),lang('plugin/yiqixueba','menu_link')));
		showtagheader('tbody', '', true);
		
		$subtitleclass = array('class="td25"', 'class="td25"', 'style="width:260px"', '');
		foreach($menus[$menus_type] as $mk=>$row ){
			$sub_menu = $row['submenu'];
			$sub_menu = array_sort($sub_menu,'displayorder');
			
			showtablerow('',$subtitleclass, array(

				(count($sub_menu) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$mk.'\', this)">[+]</a>' : '').(count($sub_menu) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$mk\" />"),

				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$mk]\" value=\"$row[displayorder]\">",

				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$mk]\" value=\"".$row['title']."\">"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, '$mk')\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_submenu')."</a></div>",

				"",

				'',

			));
			showtagheader('tbody', 'subnav_'.$mk, false);
			foreach ($sub_menu  as $kk => $subrow ){
				$modlist = '<select name="modfilenew['.$kk.']"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
				foreach ( $mokuais as $k => $v ){
					$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$k.'/'.$v['currentversion'].'/'.'node.xml'));
					foreach ($nodes as $k1 => $v1 ){
						list($nt,$nn) = explode('_',$k1);
						if($nt == $menus_type && $nn){
							$modlist .= '<option value="'.$k.'_'.$k1.'" '.($subrow['modfile'] == $k.'_'.$k1 ? ' selected':'' ).'>'.$v['name'].'('.$v['currentversion'].')-'.$v1['title'].'</option>';
						}
					}
				}
				$modlist .= '</select>';
				showtablerow('', $subtitleclass, array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$kk\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$kk]\" value=\"$subrow[displayorder]\">",
					"<div class=\"board\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$kk]\" value=\"".$subrow['title']."\"></div>",
					$modlist.'<input type="hidden" name="upidnew['.$kk.']" value="'.$mk.'" />',
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"linknew[$kk]\" value=\"".$subrow['link']."\">",
					));
			}
			
			showtagfooter('tbody');
		}
		showtagfooter('tbody');
		echo '<tr><td colspan="1"></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_menu').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		$modlist = '<select name="newmodfile[]"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
		foreach ( $mokuais as $k => $v ){
			$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$k.'/'.$v['currentversion'].'/'.'node.xml'));
			foreach ($nodes as $k1 => $v1 ){
				list($nt,$nn) = explode('_',$k1);
				if($nt == $menus_type && $nn){
					$modlist .= '<option value="'.$k.'_'.$k1.'">'.$v['name'].'('.$v['currentversion'].')-'.$v1['title'].'</option>';
				}
			}
		}
		$modlist .= '</select>';
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newtitle[]" value="" size="15" type="text" class="txt">'],[3, '']],
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<div class=\"board\"><input name="newtitle[]" value="" size="15" type="text" class="txt"></div>'], [1,'$modlist'], [1, '<input name="newlink[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupid[]" value="{1}" />'],[1, '']]
	];
</script>
EOT;
	}else{
		//更新菜单
		if(is_array($_GET['titlenew'])) {
			foreach($_GET['titlenew'] as $id => $title) {
				$title = trim(dhtmlspecialchars($title));
				$linknew = trim(dhtmlspecialchars($_GET['linknew'][$id]));
				$displayordernew = intval($_GET['displayordernew'][$id]);
				$upidnew = trim($_GET['upidnew'][$id]);
				$statusnew = intval($_GET['statusnew'][$id]);
				$modfilenew = trim($_GET['modfilenew'][$id]);
				if(!empty($title) && !$upidnew){
					$menus[$menus_type][$id]['title'] = $title;
					$menus[$menus_type][$id]['displayorder'] = $displayordernew;
				}
				if($upidnew && !empty($title) && !empty($modfilenew)){
					$data = array(
						'displayorder' => $displayordernew,
						'title' => $title,
						'link' => $linknew,
						'modfile' => $modfilenew,
					);
					$menus[$menus_type][$upidnew]['submenu'][$id] = $data;
					$menus[$menus_type][$upidnew]['submenu'] = array_sort($menus[$menus_type][$upidnew]['submenu'],'displayorder');
				}
			}
		}
		//新建菜单
		if(is_array($_GET['newtitle'])) {
			foreach($_GET['newtitle'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newlink = trim(dhtmlspecialchars($_GET['newlink'][$k]));
				$newmodfile = trim(dhtmlspecialchars($_GET['newmodfile'][$k]));
				$newupid = trim($_GET['newupid'][$k]);
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				if(!empty($v) && !$newupid ){
					$mid = random(10);
					$menus[$menus_type][$mid]['displayorder'] = $newdisplayorder;
					$menus[$menus_type][$mid]['title'] = $v;
				}
				if($newupid && !empty($v) && !empty($newmodfile)) {
					$smid = random(10);
					$menus[$menus_type][$newupid]['submenu'][$smid]['displayorder'] = $newdisplayorder;
					$menus[$menus_type][$newupid]['submenu'][$smid]['title'] = $v;
					$menus[$menus_type][$newupid]['submenu'][$smid]['link'] = $newlink;
					$menus[$menus_type][$newupid]['submenu'][$smid]['modfile'] = $newmodfile;
					$menus[$menus_type][$newupid]['submenu'] = array_sort($menus[$menus_type][$newupid]['submenu'],'displayorder');
				}
			}
		}
		//删除菜单
		if($ids = dimplode($_GET['delete'])) {
			$ida = explode(',',$ids);
			foreach ( $menus[$menus_type] as $k => $v ){
				if(in_array("'".$k."'",$ida)){
					unset($menus[$menus_type][$k]);
				}
				foreach ( $v['submenu'] as $k1 => $v1 ){
					if(in_array("'".$k1."'",$ida)){
						unset($menus[$menus_type][$k]['submenu'][$k1]);
					}
				}
			}
		}
		$menus[$menus_type] = array_sort($menus[$menus_type],'displayorder');
		file_put_contents (MOKUAI_DIR."/menus.xml",diconv(array2xml($menus, 1),"UTF-8", $_G['charset']."//IGNORE"));
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','menu_edit_succeed'), 'action='.$this_page.'&subop=menulist', 'succeed');
	}
}
?>