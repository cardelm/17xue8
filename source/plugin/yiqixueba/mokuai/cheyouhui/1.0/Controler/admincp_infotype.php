<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$subops = array('list','export','shengcheng');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$infotypeid = getgpc('infotypeid');
$cyhtable_info = C::t(GM('cheyouhui_infotype'))->fetch($infotypeid);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','infotype_list_tips'));
		showformheader($this_page);
		showtableheader(lang('plugin/yiqixueba','infotype_list'));
		showsubtitle(array('','display_order', lang('plugin/yiqixueba','infotypename'),lang('plugin/yiqixueba','infotypetitle'),lang('plugin/yiqixueba','status'),''));
		$infotypes_row = C::t(GM('cheyouhui_infotype'))->range();
		foreach($infotypes_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td25"', 'style="width=90px;"','style="width=90px;"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[infotypeid]\" />",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$row[infotypeid]]\" value=\"$row[displayorder]\">",
				$row['infotypename'],
				"<input type=\"text\" class=\"txt\" name=\"titlenew[$row[infotypeid]]\" value=\"".$row['infotypetitle']."\" >",
				"<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$row[infotypeid]]\" value=\"1\" ".($row['status'] ? ' checked="checked"' : '')." />",
				$row['status'] ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=export&infotypeid=$row[infotypeid]\" >".lang('plugin/yiqixueba','export')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shengcheng&infotypeid=$row[infotypeid]\" >".lang('plugin/yiqixueba','shengcheng')."</a>" : "",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_infotype').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'],[1, '<input name="newname[]" value="" size="15" type="text">'],[1, '<input name="newtitle[]" value="" size="15" type="text">'],[4,'']],
	];
</script>
EOT;
	}else{
		
		if(is_array($_GET['titlenew'])){
			foreach($_GET['titlenew'] as $k=>$v ){
				$v = dhtmlspecialchars(trim($v));
				$displayordernew = intval($_GET['displayordernew'][$k]);
				$statusnew = intval($_GET['statusnew'][$k]);
				if($v){
					$data = array(
						'infotypetitle' => $v,
						'displayorder' => $displayordernew,
						'status' => $statusnew,
					);
					
					C::t(GM('cheyouhui_infotype'))->update($k,$data);
				}
			}
		}
		if(is_array($_GET['newname'])){
			foreach($_GET['newname'] as $k=>$v ){
				$v = dhtmlspecialchars(trim($v));
				$newtitle = dhtmlspecialchars(trim($_GET['newtitle'][$k]));
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				if($v && $newtitle){
					$data = array(
						'infotypename' => $v,
						'infotypetitle' => $newtitle,
						'displayorder' => $newdisplayorder,
					);
					C::t(GM('cheyouhui_infotype'))->insert($data);
				}
			}
		}
		if($_GET['delete']) {
			C::t(GM('cheyouhui_infotype'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_infotype_succeed'), 'action='.$this_page, 'succeed');
	}

}elseif($subop == 'export') {
}elseif($subop == 'shengcheng') {
	//找到追加字段的方法，待定
	$tablename = 'y_'.md5($sitekey.'cheyouhui_'.$cyhtable_info['infotypename']);
	$field_info = C::t(GM('cheyouhui_field'))->fetch_all_by_infotype($cyhtable_info['infotypename']);
	dump($field_info);
	$fields = "`".$cyhtable_info['infotypename']."id` smallint(6) NOT NULL auto_increment,\n";
	foreach ($field_info as $k => $v ){
		$fields .= "`".$v['name']."` ";
		if($v['type']=='number'){
			$fields .= "smallint(6)";
		}
		$fields .= " NOT NULL ,\n";
	}
	$fields .= "PRIMARY KEY  (`".$cyhtable_info['infotypename']."id`)";
	dump($fields);
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	//cpmsg(lang('plugin/yiqixueba','edit_infotype_succeed'), 'action='.$this_page, 'succeed');

}

?>