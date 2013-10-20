<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;
//dump($this_page);

$subops = array('mokuailist','mokuaiedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = C::t(GM('main_mokuai'))->fetch_by_mokuaiid($mokuaiid);

//dump($mokuai_info);
//dump(C::t(GM('main_mokuai'))->fetch_all());
if($subop == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],'mokuai_list_tips'));
		showformheader($this_page.'&subop=mokuailist');
		showtableheader(lang('plugin/'.$plugin['identifier'],'mokuai_list'));
		showsubtitle(array('', lang('plugin/'.$plugin['identifier'],'mokuai_name'),lang('plugin/'.$plugin['identifier'],'mokuai_mokuai'),lang('plugin/'.$plugin['identifier'],'status'),''));
		$mokuais_row = C::t(GM('main_mokuai'))->fetch_all();
		foreach($mokuais_row as $k=>$row ){
			$vervalues = array();
			$vervalues[0] = 0;
			$vert = '';

			//$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." order by displayorder asc");
			//while($row1 = DB::fetch($query1)) {
				//$vert .= in_array($row1['mokuaiid'],dunserialize($row['versions'])) ? ($row1['name'].'-'.$row1['version'].'&nbsp;&nbsp;') : '';
			//}
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td25"'), array(
				'',
				'<span class="bold">'.$row['identifier'].'</span>',
				$vert,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['mokuaiid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr" >'.lang('plugin/'.$plugin['identifier'],'add_mokuai').'</a></div></td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
//		DB::update(GM('main_mokuai'), array('available'=>0));
//		foreach( getgpc('vernew') as $k=>$v ){
//			if($v){
//				DB::update(GM('main_mokuai'), array('available'=>1),array('mokuaiid'=>$k));
//			}
//		}
//		foreach(getgpc('newdisplayorder') as $k=>$v ){
//			DB::update(GM('main_mokuai'), array('displayorder'=>$v),array('mokuaiid'=>$k));
//		}
//		cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_main_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
	}
}elseif ($subop == 'mokuaiedit'){
//	if(!submitcheck('submit')) {
//		$vers = $vervalues = array();
//		$vers[0] = $vervalues[0] = 0;
//		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." order by displayorder asc");
//		//while($row = DB::fetch($query)) {
//			//$vers[] = array($row['mokuaiid'],$row['name'].'-'.$row['version']);
//		//}
//		showtips(lang('plugin/'.$plugin['identifier'],$mokuaiid ?'edit_mokuai_tips':'add_mokuai_tips'));
//		showformheader($this_page.'&subop=mokuaiedit');
//		showtableheader(lang('plugin/'.$plugin['identifier'],'mokuai_edit'));
//		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
//		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_name'),'name',$mokuai_info['mokuainame'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuai_name_comment'),'','',true);
//		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_mokuai'),array('versions',$vers),dunserialize($mokuai_info['versions']),'mcheckbox','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_version_comment'),'','',true);
//		showsubmit('submit');
//		showtablefooter();
//		showformfooter();
//	} else {
//		$mokuai_name	= dhtmlspecialchars(trim($_GET['name']));
//		if(!$mokuai_name){
//			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_name_invalid'), '', 'error');
//		}
//		$data = array(
//			'mokuainame' => $mokuai_name,
//			'status' => 1,
//			'versions' => serialize($_POST['versions']),
//		);
//		if($mokuaiid){
//			$data['updatetime'] = time();
//			DB::update(GM('main_mokuai'), $data,array('mokuaiid'=>$mokuaiid));
//		}else{
//			$data['createtime'] = time();
//			DB::insert(GM('main_mokuai'), $data);
//		}
//
//		cpmsg(lang('plugin/'.$plugin['identifier'],'add_mokuai_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
//	}
}
?>