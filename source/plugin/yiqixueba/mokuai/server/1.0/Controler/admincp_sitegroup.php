<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('sitegrouplist','sitegroupedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$sitegroupid = getgpc('sitegroupid');
$sitegroup_info = C::t(GM('server_sitegroup'))->fetch_by_sitegroupid($sitegroupid);

if($subop == 'sitegrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','sitegroup_list_tips'));
		showformheader($this_page.'&subop=sitegrouplist');
		showtableheader(lang('plugin/yiqixueba','sitegroup_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','sitegroup_sname'),lang('plugin/yiqixueba','sitegroup_access'),lang('plugin/yiqixueba','status'),''));
		$mokuais_row = C::t(GM('server_sitegroup'))->fetch_all();
		foreach($mokuais_row as $k=>$row ){
			$vervalues = array();
			$vervalues[0] = 0;
			$vert = '';

			$menus_admincp = C::t(GM('main_menus'))->fetch_all('admincp',0,'server');
			foreach($menus_admincp as $mk=>$mrow ){
				$sub_menu = C::t(GM('main_menus'))->fetch_all('admincp',$mrow['menuid'],'server');
				foreach ($sub_menu  as $kk => $subrow ){
					$vert .= in_array($subrow['menuid'],dunserialize($row['versions'])) ? ('<div style="width:60px;float: left;">'.$subrow['title'].'</div>') : '';
				}
			}
			showtablerow('', array('class="td25"', 'style="width:100px"', 'valign="top" style="width:610px"','class="td25"'), array(
				$row['systemgroup'] ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[sitegroupid]\" />",
				'<span class="bold">'.$row['sitegroupname'].'</span>',
				$vert,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['sitegroupid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupedit&sitegroupid=$row[sitegroupid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupedit" class="addtr" >'.lang('plugin/yiqixueba','add_sitegroup').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		DB::update(yiqixueba_template('main_sitegroup'), array('available'=>0));
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				DB::update(yiqixueba_template('main_sitegroup'), array('available'=>1),array('sitegroupid'=>$k));
			}
		}
		foreach(getgpc('newdisplayorder') as $k=>$v ){
			DB::update(yiqixueba_template('main_sitegroup'), array('displayorder'=>$v),array('sitegroupid'=>$k));
		}
		cpmsg(lang('plugin/yiqixueba','sitegroup_main_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupedit'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba',$sitegroupid ?'edit_sitegroup_tips':'add_sitegroup_tips'));
		showformheader($this_page.'&subop=sitegroupedit');
		showtableheader(lang('plugin/yiqixueba','sitegroup_option'));
		$sitegroupid ? showhiddenfields(array('sitegroupid'=>$sitegroupid)) : '';
		showsetting(lang('plugin/yiqixueba','sitegroup_name'),'name',$sitegroup_info['sitegroupname'],'text','',0,lang('plugin/yiqixueba','sitegroup_name_comment'),'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','sitegroup_access'));
		$menus_admincp = C::t(GM('main_menus'))->fetch_all('admincp',0,'server');
		foreach($menus_admincp as $mk=>$row ){
			$sub_menu = C::t(GM('main_menus'))->fetch_all('admincp',$row['menuid'],'server');
			$vers = array();
			foreach ($sub_menu  as $kk => $subrow ){
				list($mokuai) = explode("_",$subrow['modfile']);
				$vers[] = array($subrow['menuid'],$subrow['title'].'('.$mokuai.')');
			}
			
			showsetting($row['title'],array('versions',$vers),dunserialize($sitegroup_info['versions']),'mcheckbox','',0,lang('plugin/yiqixueba','sitegroup_access_comment'),'','',true);
		}
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
			C::t(GM('server_sitegroup'))->update($sitegroupid, $data);
		}else{
			$data['createtime'] = time();
			C::t(GM('server_sitegroup'))->insert($data);
		}

		cpmsg(lang('plugin/yiqixueba','add_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}
?>