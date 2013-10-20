<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subop = getgpc('subop');
$subops = array('sitelist','siteedit','sitegroup','mokuai');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$siteid = getgpc('siteid');
$site_info = $siteid ? DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_site')." WHERE siteid=".$siteid) : array();

//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_sitegroup'));
//while($row = DB::fetch($query)) {
	//$sitegroups[$row['sitegroupid']] = $row;
//}

//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." WHERE available = 1 and identifier <> 'server' order by displayorder asc");
//while($row = DB::fetch($query)) {
	//if($row['identifier']!='server'){
		//$mokuai_array[$row['mokuaiid']] = $row;
	//}
//}

if($subop == 'sitelist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','site_list_tips'));
		showformheader($this_page.'&subop=sitelist');
		showtableheader('search');
		echo '<tr><td>';
		echo "</span><input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','site_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','site_info'),lang('plugin/yiqixueba','site_mokuai'),lang('plugin/yiqixueba','status'),''));
		$sk = 1;
		foreach (C::t(GM('main_site'))->fetch_all()  as $k => $row ){
			$sitegroup_select = '<select name="newsitegroup[]">';
			foreach($sitegroups as $k=>$v ){
				$sitegroup_select .= '<option value="'.$k.'"'.($k == $row['sitegroup'] ? ' selected' : '' ).'>'.$v['sitegroupname'].'</option>';
			}
			$sitegroup_select .= '</select>';
			$mokuai_text = '';
			$mokuai_versions = dunserialize($row['versions']);
			$mk = 1;
			foreach ($mokuai_versions as $k => $v ){
				if(in_array($v,dunserialize($row['install']))){
					$mokuai_text .= '<span class="bold">'.$mokuai_array[$v]['name'].'-'.$mokuai_array[$v]['version'].'</span>&nbsp;&nbsp;';
				}else{
					$mokuai_text .= ceil(count($mokuai_versions)/4).$mokuai_array[$v]['name'].'-'.$mokuai_array[$v]['version'].'&nbsp;&nbsp;';
				}
				//dump($mk);
				$mk++;
			}
			showtablerow('', array('class="td25"', 'valign="top" style="width:320px"', 'class="td28"', 'class="td25"', 'class="td28"'), array(
				$sk.'<INPUT type="hidden" name="newsiteid[]" value="'.$row['siteid'].'">',
				$row['sitekey'].'<br /><span class="bold">'.$row['siteurl'].'</span><br />'.$row['version'].'&nbsp;&nbsp;'.$row['charset'].'<br />'.dgmdate($row['installtime']).'&nbsp;&nbsp;'.$sitegroup_select,
				$mokuai_text,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['siteid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=siteedit&siteid=$row[siteid]\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuai&siteid=$row[siteid]\" >".lang('plugin/yiqixueba','mokuai')."</a>&nbsp;&nbsp;",
			));
			$sk++;
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=siteedit" class="addtr" >'.lang('plugin/yiqixueba','add_site').'</a></div></td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		DB::update('yiqixueba_server_site', array('status'=>0));
		foreach( getgpc('statusnew') as $k=>$v ){
			if($v){
				DB::update('yiqixueba_server_site', array('status'=>1),array('siteid'=>$k));
			}
		}
		$siteid_array = getgpc('newsiteid');
		foreach(getgpc('newsitegroup') as $k=>$v ){
			if(DB::result_first("SELECT sitegroup FROM ".DB::table('yiqixueba_server_site')." WHERE siteid=".$siteid_array[$k]) != $v){
				DB::update('yiqixueba_server_site', array('sitegroup'=>$v,'versions'=>$sitegroups[$v]['versions']),array('siteid'=>$siteid_array[$k]));
			}
		}
		cpmsg(lang('plugin/yiqixueba','site_main_succeed'), 'action='.$this_page.'&subop=sitelist', 'succeed');
	}
}elseif ($subop == 'siteedit'){
	if(!submitcheck('submit')) {
		$vers = array();
		$vers[0] = 0;
		foreach ( $mokuai_array as $k => $v ){
			$vers[] = array($v['mokuaiid'],$v['name'].'-'.$v['version']);
		}
		$sitegroup_select = '<select name="sitegroup">';
		foreach($sitegroups as $k=>$v ){
			$sitegroup_select .= '<option value="'.$k.'"'.($k == $site_info['sitegroup'] ? ' selected' : '' ).'>'.$v['sitegroupname'].'</option>';
		}
		$sitegroup_select .= '</select>';
		$siteid ? showtips(lang('plugin/yiqixueba','edit_site_tips')):showtips(lang('plugin/yiqixueba','add_site_tips'));
		showformheader($this_page.'&subop=siteedit');
		showtableheader(lang('plugin/yiqixueba','site_edit'));
		$siteid ? showhiddenfields(array('siteid'=>$siteid)) : '';
		showsetting(lang('plugin/yiqixueba','sitegroup_mokuai'),array('versions',$vers),dunserialize($site_info['versions']),'mcheckbox','',0,lang('plugin/yiqixueba','sitegroup_edit_version_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$site_sitegroup	= intval($_GET['sitegroup']);
		$data['sitegroup'] = $site_sitegroup;
		$data['versions'] = serialize($_POST['versions']);
		if($siteid){
			$data['updatetime'] = time();
			DB::update('yiqixueba_server_site', $data,array('siteid'=>$siteid));
		}else{
			$data['createtime'] = time();
			DB::insert('yiqixueba_server_site', $data);
		}
		cpmsg(lang('plugin/yiqixueba','add_site_succeed'), 'action='.$this_page.'&subop=sitelist', 'succeed');
	}
}

?>