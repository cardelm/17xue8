<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
$citys = $citys_temp = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/city.xml"));

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','city_list_tips'));
	showformheader($this_page.'&subop=list');
	showtableheader(lang('plugin/yiqixueba','city_list'));
	showsubtitle(array('', lang('plugin/yiqixueba','cityabridge'),lang('plugin/yiqixueba','cityshortname'),lang('plugin/yiqixueba','cityname')));
	//dump($citys);
	foreach($citys as $k=>$row ){
		showtablerow('', array('class="td25"','class="td25"', 'class="td25"', ''), array(
			"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\" />",
			'<span class="bold">'.$row['abridge'].'</span>',
			"<input type=\"text\" class=\"txt\" size=\"15\" name=\"shortnamenew[$k]\" value=\"".$row['shortname']."\" >",
			$row['city'],
		));
	}
	echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_city').'</a></div></td></tr>';
	showsubmit('submit', 'submit', 'del');
	showtablefooter();
	showformfooter();
	echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1, '<input name="newabridge[]" value="" size="3" type="text">'],[1, '<input name="newshortname[]" value="" size="3" type="text">'],[1, '<input name="newcity[]" value="" size="15" type="text">']],
	];
</script>
EOT;
}else{
		//修改城市
		if(is_array($_GET['shortnamenew'])) {
			foreach($_GET['shortnamenew'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$citys_temp[$k]['shortname'] = $v;
			}
		}
		//新建城市
		if(is_array($_GET['newabridge'])) {
			foreach($_GET['newabridge'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$shortname = trim(dhtmlspecialchars($_GET['newshortname'][$k]));
				$city = trim(dhtmlspecialchars($_GET['newcity'][$k]));
				if($v && $shortname && $city ){
					$citys_temp[] = array('abridge'=>$v,'shortname'=>$shortname,'city'=>$city);
				}
			}
		}
		
		//城市删除
		foreach( getgpc('delete') as $k=>$v ){
			if($v){
				unset($citys_temp[$v]);
			}
		}
		if($citys != $citys_temp){
			$citys = $citys_temp;
			$citys = array_sort($citys,'abridge','asc');
			file_put_contents (MOKUAI_DIR."/server/1.0/Data/city.xml",diconv(array2xml($citys, 1),"UTF-8", $_G['charset']."//IGNORE"));
		}
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','edit_city_succeed'), 'action='.$this_page, 'succeed');
}



?>