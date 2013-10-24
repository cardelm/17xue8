<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('shoplist','shopedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


$shopid = getgpc('shopid');
//$shop_info = $shopid ? DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_shop')." WHERE shopid=".$shopid) : array();

if($subop == 'shoplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','shop_list_tips'));
		showformheader($this_page.'&subop=shoplist');
		showtableheader(lang('plugin/yiqixueba','shop_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','shop_name'),lang('plugin/yiqixueba','shop_mokuai'),lang('plugin/yiqixueba','status'),''));
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_shop')." order by shopid asc");
		$kk = 1;
		while($row = DB::fetch($query)) {
			$vervalues = array();
			$vervalues[0] = 0;
			$vert = '';
//			$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." order by displayorder asc");
//			while($row1 = DB::fetch($query1)) {
//				$vert .= in_array($row1['mokuaiid'],dunserialize($row['versions'])) ? ($row1['name'].'-'.$row1['version'].'&nbsp;&nbsp;') : '';
//			}
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td25"'), array(
				$kk,
				'<span class="bold">'.$row['shopname'].'</span>',
				$vert,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['shopid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&shopid=$row[shopid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
			$kk++;
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=shopedit" class="addtr" >'.lang('plugin/yiqixueba','add_shop').'</a></div></td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		DB::update('yiqixueba_shop', array('status'=>0));
		foreach(getgpc('statusnew') as $k=>$v ){
			if($v){
				DB::update('yiqixueba_shop', array('status'=>1),array('shopid'=>$k));
			}
		}
		cpmsg(lang('plugin/yiqixueba','shop_main_succeed'), 'action='.$this_page.'&subop=shoplist', 'succeed');
	}
}elseif ($subop == 'shopedit'){
	if(!submitcheck('submit')) {

		$shopid ? showtips(lang('plugin/yiqixueba','edit_shop_tips')): showtips(lang('plugin/yiqixueba','add_shop_tips'));
		showformheader($this_page.'&subop=shopedit');
		showtableheader(lang('plugin/yiqixueba','shop_edit'));
		$shopid ? showhiddenfields(array('shopid'=>$shopid)) : '';
		showsetting(lang('plugin/yiqixueba','shop_name'),'name',$shop_info['shopname'],'text','',0,lang('plugin/yiqixueba','shop_name_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shop_mokuai'),array('versions',$vers),dunserialize($shop_info['versions']),'mcheckbox','',0,lang('plugin/yiqixueba','shop_edit_version_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$shop_name	= dhtmlspecialchars(trim($_GET['name']));
		if(!$shop_name){
			cpmsg(lang('plugin/yiqixueba','shop_name_invalid'), '', 'error');
		}
		$data = array(
			'shopname' => $shop_name,
			'status' => 1,
			'versions' => serialize($_POST['versions']),
		);
		if($shopid){
			$data['updatetime'] = time();
			DB::update('yiqixueba_shop', $data,array('shopid'=>$shopid));
		}else{
			$data['createtime'] = time();
			DB::insert('yiqixueba_shop', $data);
		}

		cpmsg(lang('plugin/yiqixueba','add_shop_succeed'), 'action='.$this_page.'&subop=shoplist', 'succeed');
	}
}
?>