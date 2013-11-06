<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//安装测试
	$installdata = array();
	require_once DISCUZ_ROOT.'/source/discuz_version.php';
	$installdata['sitegroup'] = 'QiMzl6m9o6';
	$installdata['charset'] = $_G['charset'];
	$installdata['clientip'] = $_G['clientip'];
	$installdata['siteurl'] = $_G['siteurl'];
	$installdata['version'] = DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'-'.DISCUZ_FIXBUG;
	$installdata = serialize($installdata);
	$installdata = base64_encode($installdata);
	$api_url = 'http://localhost/web/17xue8/plugin.php?id=yiqixueba:api&apiaction=server_install&indata='.$installdata.'&sign='.md5(md5($installdata));
	$xml = @file_get_contents($api_url);
	require_once libfile('class/xml');
	$outdata = is_array(xml2array($xml)) ? xml2array($xml) : $xml;

	//dump($outdata);
//////////////////////
$subops = array('sitegrouplist','sitegroupmokuai','sitegroupnode','sitegroupmenu','sitegroupexport');
$subop = in_array($subop,$subops) ? $subop : $subops[0];
$menus_type = getgpc('menus_type');

$systemgroups = array('ViI7m2R9QM','QiMzl6m9o6','DAxEAvi2ie');
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
	foreach($v['version'] as $k1=>$v1){
		if($k != 'server' && !($k =='main' && $k1 == '1.0') ){
			$mksettings[] = array($k.'_'.$k1,$v['name'].'V'.$k1);
		}
	}
}

if($subop == 'sitegrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','sitegroup_list_tips'));
		showformheader($this_page.'&subop=sitegrouplist');
		showtableheader(lang('plugin/yiqixueba','sitegroup_list'));
		showsubtitle(array('','display_order', lang('plugin/yiqixueba','sitegroup_id'),lang('plugin/yiqixueba','sitegroup_name'),lang('plugin/yiqixueba','sitegroup_access'),lang('plugin/yiqixueba','sitegroupmenu'),''));
		foreach($sitegroups as $k=>$row ){
			foreach($mokuais as $k1=>$v1 ){
				foreach($v1['version'] as $k2=>$v2 ){
					if(in_array($k1.'_'.$k2,$row['installmokuai']) && $k1.'_'.$k2 != 'main_1.0' || in_array($k1.'_'.$k2,$row['upgrademokuai'] )){
						if(in_array($k1.'_'.$k2,$row['installmokuai']) && in_array($k1.'_'.$k2,$row['upgrademokuai'])){
							$mktext[$k] .= '<span class="bold">'.$v1['name'].'V'.$k2.'</span>';
						}else{
							$mktext[$k] .= $v1['name'].'V'.$k2;
						}
						$mktext[$k] .= '&nbsp;&nbsp;';
					}
				}
			}
			showtablerow('', array('class="td25"','class="td25"', 'style="width:100px"','style="width:100px"', 'valign="top" style="width:510px"','',''), array(
				in_array($k,$systemgroups) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\" />",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$k]\" value=\"".intval($row['displayorder'])."\">",
				$k,
				'<span class="bold">'.$row['name'].'</span>',
				in_array($k,$systemgroups) ? ($k != $systemgroups[0] ? lang('plugin/yiqixueba','all_mokuai') : lang('plugin/yiqixueba','no_mokuai')) : $mktext[$k],
				$row['defaultmenu'] ? lang('plugin/yiqixueba','customize') : lang('plugin/yiqixueba','default'),
				(!in_array($k,$systemgroups) ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupmokuai&menus_type=$menus_type&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','sitegroupmokuai')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupnode&menus_type=$menus_type&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','sitegroupnode')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupmenu&menus_type=$menus_type&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','sitegroupmenu')."</a>&nbsp;&nbsp;" : '')."<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupexport&sitegroupid=".$k."\" >".lang('plugin/yiqixueba','export')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupmokuai" class="addtr" >'.lang('plugin/yiqixueba','add_sitegroup').'</a></div></td></tr>';
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
}elseif ($subop == 'sitegroupmokuai'){
	$step = 1;
	echo '<style>.floattopempty { height: 20px !important; height: auto; } </style>';
	showsubmenusteps(lang('plugin/yiqixueba',$sitegroupid ? 'edit_sitegroup' : 'add_sitegroup'), array(
		array(lang('plugin/yiqixueba','sitegroup_select_mokuai'), $step == 1),
		array(lang('plugin/yiqixueba','sitegroup_select_node'), $step == 2),
		array(lang('plugin/yiqixueba','sitegroup_select_menu'), $step == 3)
	));
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','select_mokuai_tips'));
		showformheader($this_page.'&subop=sitegroupmokuai');
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
			array_unshift($installmks,'main_1.0');
		}else{
			$installmks = array('main_1.0');
		}
		$sitegroups_temp = $sitegroups;
		$sitegroups_temp[$sid]['name'] = $sitegroup_name;
		$sitegroups_temp[$sid]['installmokuai'] = $installmks;
		if(!$sitegroupid){
			$sitegroups_temp[$sid]['defaultmenu'] = 0;
		}
		$sitegroups_temp[$sid]['upgrademokuai'] = $_POST['upgrademokuai'];
		$enmokuais = array_unique(array_merge($sitegroups_temp[$sitegroupid]['installmokuai'],$sitegroups_temp[$sitegroupid]['upgrademokuai']));
		if($sitegroups_temp != $sitegroups){
			$sitegroups = $sitegroups_temp;
			$sitegroups = array_sort($sitegroups,'displayorder');
			file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','sitegroup_select_mokuai_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupnode'){
	if(!$sitegroups[$sitegroupid]['upgrademokuai'] ){
		cpmsg(lang('plugin/yiqixueba','upgrademokuai_invalid'), '', 'error');
	}
	$step = 2;
	echo '<style>.floattopempty { height: 20px !important; height: auto; } </style>';
	showsubmenusteps(lang('plugin/yiqixueba',$sitegroupid ? 'edit_sitegroup' : 'add_sitegroup'), array(
		array(lang('plugin/yiqixueba','sitegroup_select_mokuai'), $step == 1),
		array(lang('plugin/yiqixueba','sitegroup_select_node'), $step == 2),
		array(lang('plugin/yiqixueba','sitegroup_select_menu'), $step == 3)
	));
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','select_node_tips'));
		showformheader($this_page.'&subop=sitegroupnode');
		showtableheader(lang('plugin/yiqixueba','sitegroup_baseinfo'));
		showhiddenfields(array('sitegroupid'=>$sitegroupid));
		showsetting(lang('plugin/yiqixueba','sitegroup_name'),'','',$sitegroups[$sitegroupid]['name'],'',0,'','','',true);
		$enmokuais = array_unique(array_merge($sitegroups[$sitegroupid]['upgrademokuai'],$sitegroups[$sitegroupid]['installmokuai']));
		showsetting(lang('plugin/yiqixueba','enuser_mokuai'),array('installmokuai',$mksettings),$enmokuais,'mcheckbox','disabled="disabled"',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','sitegroup_node_option'));
		$initnodes = $sitegroups[$sitegroupid]['nodes'] ? 0 : 1;

		foreach($enmokuais as $k=>$v ){
			list($mk,$ver) = explode("_",$v);
			$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$mk.'/'.$ver.'/node.xml'));
			$sitegroupnodes = array();
			foreach($nodes as $k1=>$v1){
				list($k2,$v2) = explode('_',$k1);
				$sitegroupnodes[] = array($v.'_'.$k1,$v1['title'].'('.$k2.')');
				if($initnodes){
					$sitegroups[$sitegroupid]['nodes'][] = $v.'_'.$k1;
				}
			}
			if($v == 'shop'){
				$shoptemp_s = 'yes';
			}
			showsetting($mokuais[$mk]['name'].'-V'.$ver,array('nodes',$sitegroupnodes),$sitegroups[$sitegroupid]['nodes'],'mcheckbox','',0,'','','',true);

		}
		if($shoptemp_s == 'yes'){
			showsetting(lang('plugin/yiqixueba','shoptemp'),array('shoptemp', getshoptemp()),$sitegroups[$sitegroupid]['shoptemp'],'mcheckbox','',0,'','','',true);
		}
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$sitegroups[$sitegroupid]['nodes'] = $_POST['nodes'];
		if($_POST['shoptemp']){
			$sitegroups[$sitegroupid]['shoptemp'] = $_POST['shoptemp'];
		}
		foreach($_POST['nodes'] as $k=>$v ){
			list($m,$t,$n) = explode("_",$v);
			if(in_array($m,$sitegroups[$sitegroupid]['installmokuai'])){
				$sitegroups[$sitegroupid]['inodes'][$v] = $v;
			}elseif(in_array($m,$sitegroups[$sitegroupid]['upgrademokuai'])){
				$sitegroups[$sitegroupid]['unodes'][$v] = $v;
			}else{
				unset($sitegroups[$sitegroupid]['inodes']);
				unset($sitegroups[$sitegroupid]['unodes'][$v]);
				unset($sitegroups[$sitegroupid]['nodes'][$v]);
			}
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
		cpmsg(lang('plugin/yiqixueba','sitegroup_select_node_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupmenu'){
	$step = 3;
	echo '<style>.floattopempty { height: 20px !important; height: auto; } </style>';
	showsubmenusteps(lang('plugin/yiqixueba',$sitegroupid ? 'edit_sitegroup' : 'add_sitegroup'), array(
		array(lang('plugin/yiqixueba','sitegroup_select_mokuai'), $step == 1),
		array(lang('plugin/yiqixueba','sitegroup_select_node'), $step == 2),
		array(lang('plugin/yiqixueba','sitegroup_select_menu'), $step == 3)
	));
	$menus_type = getgpc('menus_type');
	$menus_types = array('admincp','member','yiqixueba');
	$menus_type = in_array($menus_type,$menus_types) ? $menus_type : $menus_types[0];

	if(!submitcheck('submit')) {
		$menus_link = '';
		foreach ($menus_types as $k => $v ){
			$menus_link .= '&nbsp;&nbsp;';
			if($menus_type == $v){
				$menus_link .= '<span style="color:#000000;">'.lang('plugin/yiqixueba','menutype_'.$v).'</span>';
			}else{
				$menus_link .= '<a href = "'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupmenu&sitegroupid='.$sitegroupid.'&defaultmenu=1&menus_type='.$v.'">'.lang('plugin/yiqixueba','menutype_'.$v).'</a>';
			}
		}
		showtips(lang('plugin/yiqixueba','select_menu_tips'));
		showformheader($this_page.'&subop=sitegroupmenu');
		showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
		showhiddenfields(array('sitegroupid'=>$sitegroupid));
		showsetting(lang('plugin/yiqixueba','sitegroup_name'),'','',$sitegroups[$sitegroupid]['name'],'',0,'','','',true);
		$enmokuais = array_unique(array_merge($sitegroups[$sitegroupid]['installmokuai'],$sitegroups[$sitegroupid]['upgrademokuai']));
		showsetting(lang('plugin/yiqixueba','enuser_mokuai'),array('installmokuai',$mksettings),$enmokuais,'mcheckbox','disabled="disabled"',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','sitegroup_menu_option'), 'nobottom');

		$defaultmenu = $_GET['defaultmenu'] ? $_GET['defaultmenu'] : $sitegroups[$sitegroupid]['defaultmenu'];
		showsetting(lang('plugin/yiqixueba','sitegroup_customize_menu'), array('defaultmenu', array(
			array(1, cplang('yes'), array('customize_menu' => '')),
			array(0, cplang('no'), array('customize_menu' => 'none'))
		), TRUE), $defaultmenu, 'mradio','',0,lang('plugin/yiqixueba','sitegroup_customize_menu_comment'),'','',true);
		showtablefooter();
			showtagheader('div', 'customize_menu', $defaultmenu);
			showtableheader($menus_link);
			showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','menu_title'),lang('plugin/yiqixueba','modfile'),lang('plugin/yiqixueba','menu_link')));

			$subtitleclass = array('class="td25"', 'class="td25"', 'style="width:260px"', '');
			foreach($sitegroups[$sitegroupid]['cmenus'][$menus_type] as $mk=>$row ){
				$sub_menu = $row['submenu'];
				$sub_menu = array_sort($sub_menu,'displayorder');

				showtablerow('',$subtitleclass, array(

					(count($sub_menu) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$mk.'\', this)">[-]</a>' : '').(count($sub_menu) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$mk\" />"),

					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$mk]\" value=\"$row[displayorder]\">",

					"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$mk]\" value=\"".$row['title']."\">"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, '$mk')\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_submenu')."</a></div>",

					"",

					'',

				));
				showtagheader('tbody', 'subnav_'.$mk, true);
				foreach ($sub_menu  as $kk => $subrow ){
					$modlist = '<select name="modfilenew['.$kk.']"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
					foreach ( $sitegroups[$sitegroupid]['nodes'] as $k => $v ){
						list($m,$t,$n) = explode("_",$v);
						$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$m.'/'.$mokuais[$m]['currentversion'].'/'.'node.xml'));
						if($t == $menus_type && $n){
							$modlist .= '<option value="'.$m.'_'.$n.'" '.($subrow['modfile'] == $m.'_'.$n ? ' selected':'' ).'>'.$mokuais[$m]['name'].'('.$mokuais[$m]['currentversion'].')-'.$nodes[$t.'_'.$n]['title'].'</option>';
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

			echo '<tr><td colspan="1"></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_menu').'</a></div></td></tr>';
			showtablefooter();
			showtableheader();
			showsetting(lang('plugin/yiqixueba','customize_succeed'),'customize',0,'radio','',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
			showtablefooter();
			showtagfooter('div');
		showtableheader();
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
		$modlist = '<select name="newmodfile[]"><option value="">'.lang('plugin/yiqixueba','select_modfile').'</option>';
		foreach ($sitegroups[$sitegroupid]['nodes'] as $k => $v ){
			list($m,$t,$n) = explode("_",$v);
			$nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$m.'/'.$mokuais[$m]['currentversion'].'/'.'node.xml'));
			if($t == $menus_type && $n){
				$modlist .= '<option value="'.$m.'_'.$n.'">'.$mokuais[$m]['name'].'('.$mokuais[$m]['currentversion'].')-'.$nodes[$t.'_'.$n]['title'].'</option>';
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
	} else {
		if(!intval($_GET['defaultmenu'])){

			$menus_types = array('admincp','member','yiqixueba');
			foreach($sitegroups[$sitegroupid]['nodes'] as $k=>$v ){
				list($m,$t,$n) = explode("_",$v);
				$sitegroups_menus[$t][$m]['title'] = $mokuais[$m]['name'];
				$sitegroups_menus[$t][$m]['displayorder'] = $mokuais[$m]['displayorder'];
				$temp_nodes = xml2array(file_get_contents(MOKUAI_DIR.'/'.$m.'/'.$mokuais[$m]['currentversion'].'/node.xml'));
				$sitegroups_menus[$t][$m]['submenu'][$n]['title'] = $temp_nodes[$t.'_'.$n]['title'];
				$sitegroups_menus[$t][$m]['submenu'][$n]['displayorder'] =  $temp_nodes[$t.'_'.$n]['displayorder'];
				$sitegroups_menus[$t][$m]['submenu'][$n]['modfile'] =  $v;
			}
			$sitegroups[$sitegroupid]['menus'] = $sitegroups_menus;
		}else{
			if(!intval($_GET['customize'])){
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
							$cmenus[$menus_type][$id]['title'] = $title;
							$cmenus[$menus_type][$id]['displayorder'] = $displayordernew;
						}
						if($upidnew && !empty($title) && !empty($modfilenew)){
							$data = array(
								'displayorder' => $displayordernew,
								'title' => $title,
								'link' => $linknew,
								'modfile' => $modfilenew,
							);
							$cmenus[$menus_type][$upidnew]['submenu'][$id] = $data;
							$cmenus[$menus_type][$upidnew]['submenu'] = array_sort($cmenus[$menus_type][$upidnew]['submenu'],'displayorder');
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
							$cmenus[$menus_type][$mid]['displayorder'] = $newdisplayorder;
							$cmenus[$menus_type][$mid]['title'] = $v;
						}
						if($newupid && !empty($v) && !empty($newmodfile)) {
							$smid = random(10);
							$cmenus[$menus_type][$newupid]['submenu'][$smid]['displayorder'] = $newdisplayorder;
							$cmenus[$menus_type][$newupid]['submenu'][$smid]['title'] = $v;
							$cmenus[$menus_type][$newupid]['submenu'][$smid]['link'] = $newlink;
							$cmenus[$menus_type][$newupid]['submenu'][$smid]['modfile'] = $newmodfile;
							$cmenus[$menus_type][$newupid]['submenu'] = array_sort($cmenus[$menus_type][$newupid]['submenu'],'displayorder');
						}
					}
				}
				//删除菜单
				if($ids = dimplode($_GET['delete'])) {
					$ida = explode(',',$ids);
					foreach ( $cmenus[$menus_type] as $k => $v ){
						if(in_array("'".$k."'",$ida)){
							unset($cmenus[$menus_type][$k]);
						}
						foreach ( $v['submenu'] as $k1 => $v1 ){
							if(in_array("'".$k1."'",$ida)){
								unset($cmenus[$menus_type][$k]['submenu'][$k1]);
							}
						}
					}
				}
				$cmenus[$menus_type] = array_sort($cmenus[$menus_type],'displayorder');
				$sitegroups[$sitegroupid]['cmenus'][$menus_type] = $cmenus[$menus_type];
				file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));

				cpmsg(lang('plugin/yiqixueba','menu_edit_succeed'), 'action='.$this_page.'&subop=sitegroupmenu&sitegroupid='.$sitegroupid.'&defaultmenu=1', 'succeed');
			}
		}
		$sitegroups[$sitegroupid]['defaultmenu'] = intval($_GET['defaultmenu']);
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups, 1),"UTF-8", $_G['charset']."//IGNORE"));
		cpmsg(lang('plugin/yiqixueba','edit_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupexport'){
	$install_file = MOKUAI_DIR.'/'.diconv($sitegroups[$sitegroupid]['name'],$_G['charset'], "gb2312//IGNORE").'_install.php';
	$install_text = "<?php\nif(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {\n\texit('Access Denied');\n}\n\nif(fsockopen('localhost', 80)){\n\t\$installdata = array();\n\trequire_once DISCUZ_ROOT.'/source/discuz_version.php';\n\t\$installdata['sitegroup'] = '$sitegroupid';\n\t\$installdata['charset'] = \$_G['charset'];\n\t\$installdata['clientip'] = \$_G['clientip'];\n\t\$installdata['siteurl'] = \$_G['siteurl'];\n\t\$installdata['version'] = DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'-'.DISCUZ_FIXBUG;\n\t\$installdata = serialize(\$installdata);\n\t\$installdata = base64_encode(\$installdata);\n\t\$api_url = '".$_G['siteurl']."plugin.php?id=yiqixueba:api&apiaction=server_install&indata='.\$installdata.'&sign='.md5(md5(\$installdata));\n\t\$xml = @file_get_contents(\$api_url);\n\trequire_once libfile('class/xml');\n\t\$outdata = is_array(xml2array(\$xml)) ? xml2array(\$xml) : \$xml;\n}else{\n\texit(lang('plugin/yiqixueba','check_connection'));\n}\n?>";
	file_put_contents($install_file,$install_text);

	if($sitegroups[$sitegroupid]['defaultmenu']){
		//dump($sitegroups[$sitegroupid]['cmenus']);
	}else{
		//dump($sitegroups[$sitegroupid]['menus']);
	}




	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	//cpmsg(lang('plugin/yiqixueba','export_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
}
?>