<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;
dump($this_page);

$subops = array('sitegrouplist','sitegroupedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$sitegroupid = getgpc('sitegroupid');
$sitegroup_info = $sitegroupid ? DB::fetch_first("SELECT * FROM ".DB::table(yiqixueba_template('main_sitegroup'))." WHERE sitegroupid=".$sitegroupid) : array();

dump(C::t('#yiqixueba#'.yiqixueba_template('main_sitegroup'))->fetch_by_sitegroupid($sitegroupid));
if($subop == 'sitegrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],'sitegroup_list_tips'));
		showformheader($this_page.'&subop=sitegrouplist');
		showtableheader(lang('plugin/'.$plugin['identifier'],'sitegroup_list'));
		showsubtitle(array('', lang('plugin/'.$plugin['identifier'],'sitegroup_name'),lang('plugin/'.$plugin['identifier'],'sitegroup_mokuai'),lang('plugin/'.$plugin['identifier'],'status'),''));
		$query = DB::query("SELECT * FROM ".DB::table(yiqixueba_template('main_sitegroup'))." order by sitegroupid asc");
		while($row = DB::fetch($query)) {
			$vervalues = array();
			$vervalues[0] = 0;
			$vert = '';

			$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." order by displayorder asc");
			while($row1 = DB::fetch($query1)) {
				$vert .= in_array($row1['mokuaiid'],dunserialize($row['versions'])) ? ($row1['name'].'-'.$row1['version'].'&nbsp;&nbsp;') : '';
			}
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td25"'), array(
				'',
				'<span class="bold">'.$row['sitegroupname'].'</span>',
				$vert,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['sitegroupid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=sitegroupedit&sitegroupid=$row[sitegroupid]\" >".lang('plugin/'.$plugin['identifier'],'edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=sitegroupedit" class="addtr" >'.lang('plugin/'.$plugin['identifier'],'add_sitegroup').'</a></div></td></tr>';
		showsubmit('submit');
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
		cpmsg(lang('plugin/'.$plugin['identifier'],'sitegroup_main_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}elseif ($subop == 'sitegroupedit'){
	if(!submitcheck('submit')) {
		$vers = $vervalues = array();
		$vers[0] = $vervalues[0] = 0;
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." order by displayorder asc");
		//while($row = DB::fetch($query)) {
			//$vers[] = array($row['mokuaiid'],$row['name'].'-'.$row['version']);
		//}
		showtips(lang('plugin/'.$plugin['identifier'],$sitegroupid ?'edit_sitegroup_tips':'add_sitegroup_tips'));
		showformheader($this_page.'&subop=sitegroupedit');
		showtableheader(lang('plugin/'.$plugin['identifier'],'sitegroup_edit'));
		$sitegroupid ? showhiddenfields(array('sitegroupid'=>$sitegroupid)) : '';
		showsetting(lang('plugin/'.$plugin['identifier'],'sitegroup_name'),'name',$sitegroup_info['sitegroupname'],'text','',0,lang('plugin/'.$plugin['identifier'],'sitegroup_name_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'sitegroup_mokuai'),array('versions',$vers),dunserialize($sitegroup_info['versions']),'mcheckbox','',0,lang('plugin/'.$plugin['identifier'],'sitegroup_edit_version_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$sitegroup_name	= dhtmlspecialchars(trim($_GET['name']));
		if(!$sitegroup_name){
			cpmsg(lang('plugin/'.$plugin['identifier'],'sitegroup_name_invalid'), '', 'error');
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

		cpmsg(lang('plugin/'.$plugin['identifier'],'add_sitegroup_succeed'), 'action='.$this_page.'&subop=sitegrouplist', 'succeed');
	}
}
?>