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
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
$mokuais = array_sort($mokuais,'displayorder');

$menus = xml2array(file_get_contents(MOKUAI_DIR."/menus.xml"));
$menus[$menus_type] = array_sort($menus[$menus_type],'displayorder');

$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/sitegroups.xml"));
$sitegroups = array_sort($sitegroups,'displayorder');

$sitegroupid = getgpc('sitegroupid');

foreach($mokuais as $k=>$v ){
	if(!in_array($k,array('main','server'))){
		$mksettings[] = array($k,$v['name'].'V'.$v['currentversion']);
	}
}

if($subop == 'sitegrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','sitegroup_list_tips'));
		showformheader($this_page.'&subop=sitegrouplist');
		showtableheader(lang('plugin/yiqixueba','sitegroup_list'));
		showsubtitle(array('','display_order', lang('plugin/yiqixueba','sitegroup_name'),lang('plugin/yiqixueba','sitegroup_access'),''));
		foreach($sitegroups as $k=>$row ){
			showtablerow('', array('class="td25"','class="td25"', 'style="width:100px"', 'valign="top" style="width:610px"',''), array(
				$row['systemgroup'] ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\" />",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$k]\" value=\"".intval($row['displayorder'])."\">",
				'<span class="bold">'.$row['name'].'</span>',
				'',
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
	$step = max(1, intval($_GET['step']));
	echo '<style>.floattopempty { height: 20px !important; height: auto; } </style>';
	showsubmenusteps(lang('plugin/yiqixueba',$sitegroupid ? 'edit_sitegroup' : 'add_sitegroup'), array(
		array(lang('plugin/yiqixueba','sitegroup_select_mokuai'), $step == 1),
		array(lang('plugin/yiqixueba','sitegroup_select_node'), $step == 2),
		array(lang('plugin/yiqixueba','sitegroup_select_menu'), $step == 3)
	));
	if($step == 1){
		if(!submitcheck('submit')) {
			showtips(lang('plugin/yiqixueba','select_mokuai_tips'));
			showformheader($this_page.'&subop=sitegroupedit&step=1');
			showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
			$sitegroupid ? showhiddenfields(array('sitegroupid'=>$sitegroupid)) : '';
			showsetting(lang('plugin/yiqixueba','sitegroup_name'),'name',$sitegroups[$sitegroupid]['name'],'text','',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','install_mokuai'),array('installmokuai',$mksettings),$sitegroups[$sitegroupid]['installmokuai'],'mcheckbox','',0,'','','',true);
			showsetting(lang('plugin/yiqixueba','upgrade_mokuai'),array('upgrademokuai',$mksettings),$sitegroups[$sitegroupid]['upgrademokuai'],'mcheckbox','',0,'','','',true);
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
			if(!$_POST['upgrademokuai']){
				cpmsg(lang('plugin/yiqixueba','upgrademokuai_invalid'), '', 'error');
			}
			if(!$sitegroupid){
				$sid = random(10);
			}else{
				$sid = $sitegroupid;
			}
			$installmks = $_POST['installmokuai'];
			if($installmks){
				array_unshift($installmks,'main');
			}else{
				$installmks = array('main');
			}
			$sitegroups[$sid]['name'] = $sitegroup_name;
			$sitegroups[$sid]['displayorder'] = 0;
			$sitegroups[$sid]['installmokuai'] = $installmks;
			$sitegroups[$sid]['upgrademokuai'] = $_POST['upgrademokuai'];
			$sitegroups = array_sort($sitegroups,'displayorder');

			echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
			file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
			cpmsg(lang('plugin/yiqixueba','sitegroup_select_mokuai_succeed'), 'action='.$this_page.'&subop=sitegroupedit&step=2&sitegroupid='.$sid, 'succeed');
		}
	}elseif($step == 2){
		if(!submitcheck('submit')) {
			showtips(lang('plugin/yiqixueba','select_node_tips'));
			showformheader($this_page.'&subop=sitegroupedit&step=2');
			showtableheader(lang('plugin/yiqixueba','sitegroup_baseinfo'));
			showhiddenfields(array('sitegroupid'=>$sitegroupid));
			showsetting(lang('plugin/yiqixueba','sitegroup_name'),'','',$sitegroups[$sitegroupid]['name'],'',0,'','','',true);
			$enmokuais = array_unique(array_merge($sitegroups[$sitegroupid]['upgrademokuai'],$sitegroups[$sitegroupid]['installmokuai']));
			showsetting(lang('plugin/yiqixueba','enuser_mokuai'),array('installmokuai',$mksettings),$enmokuais,'mcheckbox','disabled="disabled"',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
			showtablefooter();
			showtableheader(lang('plugin/yiqixueba','sitegroup_node_option'));
			$initnodes = $sitegroups[$sitegroupid]['nodes'] ? 0 : 1;
			foreach($enmokuais as $k=>$v ){
				$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$v.'/'.$mokuais[$v]['currentversion'].'/node.xml'));
				$sitegroupnodes = array();
				foreach($nodes as $k1=>$v1){
					list($k2,$v2) = explode('_',$k1);
					$sitegroupnodes[] = array($v.'_'.$k1,$v1['title'].'('.$k2.')');
					if($initnodes){
						$sitegroups[$sitegroupid]['nodes'][] = $v.'_'.$k1;
					}
				}
				showsetting($mokuais[$v]['name'].'-V'.$mokuais[$v]['currentversion'],array('nodes',$sitegroupnodes),$sitegroups[$sitegroupid]['nodes'],'mcheckbox','',0,'','','',true);

			}
			showsubmit('submit');
			showtablefooter();
			showformfooter();
		} else {
			$sitegroups[$sitegroupid]['nodes'] = $_POST['nodes'];
			echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
			file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
			cpmsg(lang('plugin/yiqixueba','sitegroup_select_node_succeed'), 'action='.$this_page.'&subop=sitegroupedit&step=3&sitegroupid='.$sitegroupid, 'succeed');
		}
	}elseif($step == 3){
		if(!submitcheck('submit')) {
			showtips(lang('plugin/yiqixueba','select_menu_tips'));
			showformheader($this_page.'&subop=sitegroupedit&step=3');
			showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
			showhiddenfields(array('sitegroupid'=>$sitegroupid));
			showsetting(lang('plugin/yiqixueba','sitegroup_name'),'','',$sitegroups[$sitegroupid]['name'],'',0,'','','',true);
			$enmokuais = array_unique(array_merge($sitegroups[$sitegroupid]['installmokuai'],$sitegroups[$sitegroupid]['upgrademokuai']));
			showsetting(lang('plugin/yiqixueba','enuser_mokuai'),array('installmokuai',$mksettings),$enmokuais,'mcheckbox','disabled="disabled"',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
			showtablefooter();
			showtableheader(lang('plugin/yiqixueba','sitegroup_menu_option'));
			showsetting(lang('plugin/yiqixueba','sitegroup_default_menu'),'defaultmenu',1,'radio','',0,lang('plugin/yiqixueba','sitegroup_default_menu_comment'),'','',true);//radio
			showsubmit('submit');
			showtablefooter();
			showformfooter();
		} else {
			if(intval($_GET['defaultmenu'])){
				$sitegroups[$sitegroupid]['menu'] = $sitegroups[$sitegroupid]['nodes'];
			}

			echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
			file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
			cpmsg(lang('plugin/yiqixueba','edit_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
		}
	}
}elseif ($subop == 'sitegroupexport'){
	$install_file = MOKUAI_DIR.'/'.diconv($sitegroups[$sitegroupid]['name'],$_G['charset'], "gb2312//IGNORE").'_install.php';
	$install_text = "<?php\nif(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {\n\texit('Access Denied');\n}\n\nif(fsockopen('localhost', 80)){\n\t\$installdata = array();\n\trequire_once DISCUZ_ROOT.'/source/discuz_version.php';\n\t\$installdata['sitegroup'] = '$sitegroupid';\n\t\$installdata['charset'] = \$_G['charset'];\n\t\$installdata['clientip'] = \$_G['clientip'];\n\t\$installdata['siteurl'] = \$_G['siteurl'];\n\t\$installdata['version'] = DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'-'.DISCUZ_FIXBUG;\n\t\$installdata = serialize(\$installdata);\n\t\$installdata = base64_encode(\$installdata);\n\t\$api_url = '".$_G['siteurl']."plugin.php?id=yiqixueba:api&apiaction=server_install&indata='.\$installdata.'&sign='.md5(md5(\$installdata));\n\t\$xml = @file_get_contents(\$api_url);\n\trequire_once libfile('class/xml');\n\t\$outdata = is_array(xml2array(\$xml)) ? xml2array(\$xml) : \$xml;\n}else{\n\texit(lang('plugin/yiqixueba','check_connection'));\n}\n?>";
	file_put_contents($install_file,$install_text);
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','export_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
}
?>