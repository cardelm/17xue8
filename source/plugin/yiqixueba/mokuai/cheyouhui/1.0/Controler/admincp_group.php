<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$groupid = getgpc('groupid');
$group_info = C::t(GM('cheyouhui_group'))->fetch($groupid);

$infotypes = C::t(GM('cheyouhui_infotype'))->range();
foreach ($infotypes as $k => $v ){
	if($v['status']){
		$infoa[$v['infotypename']] = $v['infotypetitle'];
	}
}

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','cyhgroup_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','cyhgroup_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','groupname'),lang('plugin/yiqixueba','groupquanxian'),lang('plugin/yiqixueba','status'),''));
		$groups_row = C::t(GM('cheyouhui_group'))->range();
		foreach($groups_row as $k=>$row ){
			$quanxian_t = '';
			$quanxian_a = array();
			foreach (dunserialize($row['quanxian']) as $k1 => $v1 ){
				list($infot,$op) = explode("_",$v1);
				$quanxian_a[$infot] .= lang('plugin/yiqixueba','qx_'.$op);
			}
			foreach ($quanxian_a as $k2 => $v2 ){
				$quanxian_t .= '<span class="bold">'.$infoa[$k2].':</span>'.$v2.'&nbsp;&nbsp;';
			}
			
			showtablerow('', array('class="td25"', 'style="width:100px;"', '','class="td25"','class="td25"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[groupid]\" />",
				'<span class="bold">'.$row['groupname'].'</span><input type="hidden" name="namenew['.$row['groupid'].']">',
				$quanxian_t,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['groupid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&groupid=$row[groupid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_group').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		if($_GET['delete']) {
			C::t(GM('cheyouhui_group'))->delete($_GET['delete']);
		}
		if(is_array($_GET['namenew'])){
			foreach ($_GET['namenew']  as $k => $v ){
				$data['status'] = intval($_GET['statusnew'][$k]);
				C::t(GM('cheyouhui_group'))->update($k,$data);
			}
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_group_succeed'), 'action='.$this_page.'&subop=grouplist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips($groupid ? lang('plugin/yiqixueba','edit_group_tips') : lang('plugin/yiqixueba','add_group_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','group_option'));
		$groupid ? showhiddenfields(array('groupid'=>$groupid)) : '';
		showsetting(lang('plugin/yiqixueba','groupstatus'),'status',$group_info['status'],'radio','',0,lang('plugin/yiqixueba','groupstatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','groupname'),'groupname',$group_info['groupname'],'text','',0,lang('plugin/yiqixueba','groupname_comment'),'','',true);
		
		foreach ($infotypes as $k => $v ){
			if($v['status']){
				showsetting($v['infotypetitle'],array('quanxian', array(
					array($v['infotypename'].'_add', lang('plugin/yiqixueba','add')),
					array($v['infotypename'].'_del', lang('plugin/yiqixueba','del')),
					array($v['infotypename'].'_edit', lang('plugin/yiqixueba','edit')),
					array($v['infotypename'].'_cha', lang('plugin/yiqixueba','cha'))
				)),dunserialize($group_info['quanxian']),'mcheckbox','',0,'','','',true);
			}
		}

		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$groupname = dhtmlspecialchars(trim($_GET['groupname']));
		$status	= intval($_GET['status']);
		if(!$groupname){
			//cpmsg(lang('plugin/yiqixueba','groupname_invalid'), '', 'error');
		}
		if(!ispluginkey($groupname)) {
			//cpmsg(lang('plugin/yiqixueba','groupname_invalid'), '', 'error');
		}
		$data = array(
			'groupname' => $groupname,
			'status' => $status,
			'quanxian' => serialize($_GET['quanxian']),
		);
		if($groupid){
			$data['updatetime'] = time();
			C::t(GM('cheyouhui_group'))->update($groupid,$data);
		}else{
			$data['updatetime'] = time();
			C::t(GM('cheyouhui_group'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_group_succeed'), 'action='.$this_page.'&subop=grouplist', 'succeed');
	}


}

?>