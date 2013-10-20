<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('membergrouplist','membergroupedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$membergroupid = getgpc('membergroupid');
$membergroup_info = C::t(GM('main_membergroup'))->fetch_by_membergroupid($membergroupid);

if($subop == 'membergrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','membergroup_list_tips'));
		showformheader($this_page.'&subop=membergrouplist');
		showtableheader(lang('plugin/yiqixueba','membergroup_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','membergroup_sname'),lang('plugin/yiqixueba','membergroup_access'),lang('plugin/yiqixueba','status'),''));
		$mokuais_row = C::t(GM('main_membergroup'))->fetch_all();
		foreach($mokuais_row as $k=>$row ){
			$vervalues = array();
			$vervalues[0] = 0;
			$vert = '';

			$menus_member = C::t(GM('main_menus'))->fetch_all('member',0,'server');
			foreach($menus_member as $mk=>$mrow ){
				$sub_menu = C::t(GM('main_menus'))->fetch_all('member',$mrow['menuid'],'server');
				foreach ($sub_menu  as $kk => $subrow ){
					$vert .= in_array($subrow['menuid'],dunserialize($row['versions'])) ? ('<div style="width:75px;float: left;">'.$subrow['title'].'</div>') : '';
				}
			}
			showtablerow('', array('class="td25"', 'style="width:100px"', 'valign="top" style="width:610px"','class="td25"'), array(
				$row['systemgroup'] ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[membergroupid]\" />",
				'<span class="bold">'.$row['membergroupname'].'</span>',
				$vert,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['membergroupid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=membergroupedit&membergroupid=$row[membergroupid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=membergroupedit" class="addtr" >'.lang('plugin/yiqixueba','add_membergroup').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		DB::update(yiqixueba_template('main_membergroup'), array('available'=>0));
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				DB::update(yiqixueba_template('main_membergroup'), array('available'=>1),array('membergroupid'=>$k));
			}
		}
		foreach(getgpc('newdisplayorder') as $k=>$v ){
			DB::update(yiqixueba_template('main_membergroup'), array('displayorder'=>$v),array('membergroupid'=>$k));
		}
		cpmsg(lang('plugin/yiqixueba','membergroup_main_succeed'), 'action='.$this_page.'&subop=membergrouplist', 'succeed');
	}
}elseif ($subop == 'membergroupedit'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba',$membergroupid ?'edit_membergroup_tips':'add_membergroup_tips'));
		showformheader($this_page.'&subop=membergroupedit');
		showtableheader(lang('plugin/yiqixueba','membergroup_option'));
		$membergroupid ? showhiddenfields(array('membergroupid'=>$membergroupid)) : '';
		showsetting(lang('plugin/yiqixueba','membergroup_name'),'name',$membergroup_info['membergroupname'],'text','',0,lang('plugin/yiqixueba','membergroup_name_comment'),'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','membergroup_access'));
		$menus_member = C::t(GM('main_menus'))->fetch_all('member',0,'server');
		foreach($menus_member as $mk=>$row ){
			$sub_menu = C::t(GM('main_menus'))->fetch_all('member',$row['menuid'],'server');
			$vers = array();
			foreach ($sub_menu  as $kk => $subrow ){
				list($mokuai) = explode("_",$subrow['modfile']);
				$vers[] = array($subrow['menuid'],$subrow['title'].'('.$mokuai.')');
			}
			
			showsetting($row['title'],array('versions',$vers),dunserialize($membergroup_info['versions']),'mcheckbox','',0,lang('plugin/yiqixueba','membergroup_access_comment'),'','',true);
		}
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$membergroup_name	= dhtmlspecialchars(trim($_GET['name']));
		if(!$membergroup_name){
			cpmsg(lang('plugin/yiqixueba','membergroup_name_invalid'), '', 'error');
		}
		$data = array(
			'membergroupname' => $membergroup_name,
			'status' => 1,
			'versions' => serialize($_POST['versions']),
		);
		if($membergroupid){
			$data['updatetime'] = time();
			C::t(GM('main_membergroup'))->update($membergroupid, $data);
		}else{
			$data['createtime'] = time();
			C::t(GM('main_membergroup'))->insert($data);
		}

		cpmsg(lang('plugin/yiqixueba','add_membergroup_succeed'), 'action='.$this_page.'&subop=membergrouplist', 'succeed');
	}
}
?>