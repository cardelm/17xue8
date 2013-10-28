<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('sitegrouplist','sitegroupedit','sitegroupexport');
$subop = in_array($subop,$subops) ? $subop : $subops[0];
$menus_type = getgpc('menus_type');

$menus_types = array('admincp','member','yiqixueba');
$menus_type = in_array($menus_type,$menus_types) ? $menus_type : $menus_types[0];

require_once libfile('class/xml');
$menus = xml2array(file_get_contents(MOKUAI_DIR."/menus.xml"));
$menus[$menus_type] = array_sort($menus[$menus_type],'displayorder');

$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/sitegroups.xml"));
$sitegroups = array_sort($sitegroups,'displayorder');


$sitegroupid = getgpc('sitegroupid');

if($subop == 'sitegrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','sitegroup_list_tips'));
		showformheader($this_page.'&subop=sitegrouplist');
		showtableheader(lang('plugin/yiqixueba','sitegroup_list'));
		showsubtitle(array('','display_order', lang('plugin/yiqixueba','sitegroup_name'),lang('plugin/yiqixueba','sitegroup_access'),''));
		foreach($sitegroups as $k=>$row ){
			$admincpnum = count($sitegroups[$k]['admincp']) ? count($sitegroups[$k]['admincp'])-1 : 0;
			$membernum = count($sitegroups[$k]['member']) ? count($sitegroups[$k]['member'])-1 : 0;
			$yiqixuebanum = count($sitegroups[$k]['yiqixueba']) ? count($sitegroups[$k]['yiqixueba'])-1 : 0;
			showtablerow('', array('class="td25"','class="td25"', 'style="width:100px"', 'valign="top" style="width:610px"',''), array(
				$row['systemgroup'] ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\" />",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$k]\" value=\"".intval($row['displayorder'])."\">",
				'<span class="bold">'.$row['name'].'</span>',
				lang('plugin/yiqixueba','sitegroup_admincp').'('.$admincpnum.')&nbsp;&nbsp;'.lang('plugin/yiqixueba','sitegroup_member').'('.$membernum.')&nbsp;&nbsp;'.lang('plugin/yiqixueba','sitegroup_yiqixueba').'('.$yiqixuebanum.')',
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupedit&menus_type=$menus_type&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupexport&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','export')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupedit" class="addtr" >'.lang('plugin/yiqixueba','add_sitegroup').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		foreach(getgpc('displayordernew') as $k=>$v ){
			$sitegroups[$k]['displayorder'] = $v;
		}
		if($ids = dimplode($_GET['delete'])) {
			$ida = explode(',',$ids);
			foreach ( $sitegroups as $k => $v ){
				if(in_array("'".$k."'",$ida)){
					unset($sitegroups[$k]);
				}
			}
		}
		$sitegroups = array_sort($sitegroups,'displayorder');
		file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupedit'){
	$link = '';
	foreach ( $menus_types as $k => $v ){
		$link .= '&nbsp;&nbsp;';
		if($menus_type == $v){
			$link .= '<span style="color:#000000;">'.lang('plugin/yiqixueba','sitegroup_'.$v.'_access').'</span>';
		}else{
			$link .= '<a href = "'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupedit&sitegroupid='.$sitegroupid.'&menus_type='.$v.'">'.lang('plugin/yiqixueba','sitegroup_'.$v.'_access').'</a>';
		}
	}
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba',$sitegroupid ?'edit_sitegroup_tips':'add_sitegroup_tips'));
		showformheader($this_page.'&subop=sitegroupedit');
		showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
		$sitegroupid ? showhiddenfields(array('sitegroupid'=>$sitegroupid)) : '';
		showhiddenfields(array('menus_type'=>$menus_type));
		showsetting(lang('plugin/yiqixueba','sitegroup_name'),'name',$sitegroups[$sitegroupid]['name'],'text','',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
		showtablefooter();
		showtableheader($link);
		foreach($menus[$menus_type] as $mk=>$row ){
			$sub_menu = $row['submenu'];
			$sub_menu = array_sort($sub_menu,'displayorder');
			$vers = array();
			foreach ($sub_menu  as $kk => $subrow ){
				list($mokuai) = explode("_",$subrow['modfile']);
				$vers[] = array($mk.'_'.$kk,$subrow['title'].'('.$mokuai.')');
			}
			showsetting($row['title'],array('versions',$vers),$sitegroups[$sitegroupid][$menus_type]['modfile'],'mcheckbox','',0,'','','',true);
		}
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$sitegroup_name	= dhtmlspecialchars(trim($_GET['name']));
		foreach ($sitegroups as $k => $v ){
			$sitegroupnames[] = $v['name'];
		}
		if(!$sitegroup_name ){
			cpmsg(lang('plugin/yiqixueba','sitegroup_name_invalid'), '', 'error');
		}
		if(!$sitegroupid && in_array($sitegroup_name,$sitegroupnames)){
			cpmsg(lang('plugin/yiqixueba','sitegroup_name_chongfu'), '', 'error');
		}
		if(!$sitegroupid){
			$sid = random(10);
			$sitegroups[$sid]['name'] = $sitegroup_name;
			$sitegroups[$sid]['displayorder'] = 0;
			foreach ($_POST['versions']  as $k => $v ){
				list($mm,$sm) = explode('_',$v);
				$sitegroups[$sid][$menus_type]['modfile'][$sm] = $v;
				$sitegroups[$sid][$menus_type][$sm]['menu'] = $menus[$menus_type][$mm]['title'];
				$sitegroups[$sid][$menus_type][$sm]['displayorder'] = $menus[$menus_type][$mm]['displayorder'];
				$sitegroups[$sid][$menus_type][$sm]['submenu'] = $menus[$menus_type][$mm]['submenu'][$sm];
			}
		}else{
			$sitegroups[$sitegroupid]['name'] = $sitegroup_name;
			unset($sitegroups[$sitegroupid][$menus_type]['modfile']);
			unset($sitegroups[$sitegroupid][$menus_type]);
			foreach ($_POST['versions']  as $k => $v ){
				list($mm,$sm) = explode('_',$v);
				$sitegroups[$sitegroupid][$menus_type]['modfile'][$sm] = $v;
				$sitegroups[$sitegroupid][$menus_type][$sm]['menu'] = $menus[$menus_type][$mm]['title'];
				$sitegroups[$sitegroupid][$menus_type][$sm]['displayorder'] = $menus[$menus_type][$mm]['displayorder'];
				$sitegroups[$sitegroupid][$menus_type][$sm]['submenu'] = $menus[$menus_type][$mm]['submenu'][$sm];
			}
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
		cpmsg(lang('plugin/yiqixueba',$sitegroupid ? 'edit_sitegroup_succeed' : 'add_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupexport'){
}
?>