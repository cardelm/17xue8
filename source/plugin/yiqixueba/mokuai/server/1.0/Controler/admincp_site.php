<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
$sitegroups = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/sitegroups.xml"));

//dump($sitegroups);

$subops = array('sitelist','siteedit','sitegroup','mokuai');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$siteid = getgpc('siteid');
$site_info = C::t(GM('server_site'))->fetch($siteid);


if($subop == 'sitelist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','site_list_tips'));
		showformheader($this_page.'&subop=sitelist');
		showtableheader('search');
		echo '<tr><td>';
		echo "</span><input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','site_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','site_info'),lang('plugin/yiqixueba','zhhyue'),lang('plugin/yiqixueba','sitegroup'),lang('plugin/yiqixueba','sitemokuai'),lang('plugin/yiqixueba','status'),''));
		$sk = 1;
		foreach (C::t(GM('server_site'))->range()  as $k => $row ){
			$sitegroup_text = '';
			$sitegroup_array = dunserialize($row['sitegroup']);
			foreach($sitegroup_array as $k1=>$v1 ){
				$sitegroup_text .= $sitegroups[$v1]['name'].'<br />';
			}
			showtablerow('', array('class="td25"', 'valign="top" style="width:320px"', 'style="width:60px"','class="td25"','class="td28"', 'style="width:60px"', 'class="td28"'), array(
				$sk.'<INPUT type="hidden" name="newsiteid[]" value="'.$row['siteid'].'">',
				'<span class="bold">'.$row['siteurl'].'</span><br />'.$row['version'].'&nbsp;&nbsp;'.$row['charset'].'<br />'.dgmdate($row['installtime']).'||'.dgmdate($row['updatetime']),
				'',
				$sitegroup_text,
				'',
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['siteid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=siteedit&siteid=$row[siteid]\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuai&siteid=$row[siteid]\" >".lang('plugin/yiqixueba','sitemokuai')."</a>&nbsp;&nbsp;",
			));
			$sk++;
		}
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
		foreach ($sitegroups as $k => $v ){
			$vers[] = array($k,$v['name']);
		}
		showtips(lang('plugin/yiqixueba','edit_site_tips'));
		showformheader($this_page.'&subop=siteedit');
		showtableheader(lang('plugin/yiqixueba','site_info'));
		showtablerow('', array( ''), array(
			lang('plugin/yiqixueba','siteurl:').$site_info['siteurl'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','version:').$site_info['version'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','charset:').$site_info['charset'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','installtime:').dgmdate($site_info['installtime']).'&nbsp;&nbsp;'.lang('plugin/yiqixueba','updatetime:').dgmdate($site_info['updatetime']),
		));
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','site_edit'));
		$siteid ? showhiddenfields(array('siteid'=>$siteid)) : '';
		showsetting(lang('plugin/yiqixueba','sitegroup'),array('sitegroup',$vers),dunserialize($site_info['sitegroup']),'mcheckbox','',0,lang('plugin/yiqixueba','sitegroup_edit_version_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$data['sitegroup'] = serialize($_POST['sitegroup']);
		if($siteid){
			$data['updatetime'] = time();
			C::t(GM('server_site'))->update($siteid,$data);
		}else{
			$data['createtime'] = time();
			DB::insert('yiqixueba_server_site', $data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_site_succeed'), 'action='.$this_page.'&subop=sitelist', 'succeed');
	}
}elseif ($subop == 'mokuai'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_sitemokuai_tips'));
		showformheader($this_page.'&subop=mokuai');
		showtableheader(lang('plugin/yiqixueba','site_info'));
		showtablerow('', array( ''), array(
			lang('plugin/yiqixueba','siteurl:').$site_info['siteurl'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','version:').$site_info['version'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','charset:').$site_info['charset'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','installtime:').dgmdate($site_info['installtime']).'&nbsp;&nbsp;'.lang('plugin/yiqixueba','updatetime:').dgmdate($site_info['updatetime']),
		));
		showtablefooter();
		showtableheader('search');
		echo '<tr><td>';
		echo "</span><input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','sitemokuai_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','mokuainame'),lang('plugin/yiqixueba','zhhyue'),lang('plugin/yiqixueba','sitemokuai'),lang('plugin/yiqixueba','dailiquyu'),lang('plugin/yiqixueba','status'),''));
		$siteid ? showhiddenfields(array('siteid'=>$siteid)) : '';

		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$data['sitegroup'] = serialize($_POST['sitegroup']);
		if($siteid){
			$data['updatetime'] = time();
			C::t(GM('server_site'))->update($siteid,$data);
		}else{
			$data['createtime'] = time();
			DB::insert('yiqixueba_server_site', $data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_site_succeed'), 'action='.$this_page.'&subop=sitelist', 'succeed');
	}
}

?>