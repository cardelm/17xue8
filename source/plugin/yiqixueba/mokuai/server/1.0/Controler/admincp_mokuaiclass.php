<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('mokuai_classlist','mokuai_classedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


if($subop == 'mokuai_classlist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','mokuai_class_list_tips'));
		showformheader($this_page.'&subop=mokuai_classlist');
		showtableheader(lang('plugin/yiqixueba','mokuai_class_list'));
		showsubtitle(array('', 'display_order',lang('plugin/yiqixueba','admincpmenu_name'),lang('plugin/yiqixueba','admincpmenu_title'),lang('plugin/yiqixueba','modfile'),''));
		$menus_admincp = C::t(GM('server_mokuai_class'))->fetch_all('admincp','main',0);
		foreach($menus_admincp as $mk=>$row ){
			showtablerow('', array('class="td25"', 'class="td25"', '', '',''), array(
				(count($row) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$row['menuid'].'\', this)">[+]</a>' : '')."<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[menuid]\">",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$row[menuid]]\" value=\"$row[menuid]\">",
				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[menuid]]\" value=\"".$row['name']."\"$readonly>"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, $row[menuid])\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_submenu')."</a></div>",
				"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[menuid]]\" value=\"".dhtmlspecialchars($row['title'])."\">",
				'',
			));
		}
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuai_classedit" class="addtr">'.lang('plugin/yiqixueba','add_mokuai_class').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		DB::update(yiqixueba_template('main_admincpmenu'), array('available'=>0));
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				DB::update(yiqixueba_template('main_admincpmenu'), array('available'=>1),array('admincpmenuid'=>$k));
			}
		}
		foreach(getgpc('newdisplayorder') as $k=>$v ){
			DB::update(yiqixueba_template('main_admincpmenu'), array('displayorder'=>$v),array('admincpmenuid'=>$k));
		}
		cpmsg(lang('plugin/yiqixueba','admincpmenu_main_succeed'), 'action='.$this_page.'&subop=admincpmenulist', 'succeed');
	}

}elseif($subop == 'mokuai_classedit') {
	if(!submitcheck('submit')) {
		$vers = $vervalues = array();
		$vers[0] = $vervalues[0] = 0;
		showtips(lang('plugin/yiqixueba',$sitegroupid ?'edit_sitegroup_tips':'add_sitegroup_tips'));
		showformheader($this_page.'&subop=sitegroupedit');
		showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
		$sitegroupid ? showhiddenfields(array('sitegroupid'=>$sitegroupid)) : '';
		showsetting(lang('plugin/yiqixueba','sitegroup_name'),'name',$sitegroup_info['sitegroupname'],'text','',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','sitegroup_access'),array('versions',$vers),dunserialize($sitegroup_info['versions']),'mcheckbox','',0,lang('plugin/yiqixueba','sitegroup_access_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$sitegroup_name	= dhtmlspecialchars(trim($_GET['name']));
		if(!$sitegroup_name){
			cpmsg(lang('plugin/yiqixueba','sitegroup_name_invalid'), '', 'error');
		}
		$data = array(
			'sitegroupname' => $sitegroup_name,
			'status' => 1,
			'versions' => serialize($_POST['versions']),
		);
		if($sitegroupid){
			$data['updatetime'] = time();
			DB::update(yiqixueba_template('main_sitegroup'), $data,array('sitegroupid'=>$sitegroupid));
		}else{
			$data['createtime'] = time();
			DB::insert(yiqixueba_template('main_sitegroup'), $data);
		}

		cpmsg(lang('plugin/yiqixueba','add_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}

?>