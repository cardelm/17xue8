<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once libfile('class/xml');

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','shopsort_list_tips'));
	showformheader($this_page);
	showtableheader(lang('plugin/yiqixueba','shopsort_list'));
	showsubtitle(array('', lang('plugin/yiqixueba','displayorder'),lang('plugin/yiqixueba','shopsortname'),lang('plugin/yiqixueba','shopsorttitle'),  ''));

	$shopsorts = C::t(GM('shop_shopsort'))->range();
	file_put_contents (MOKUAI_DIR."/server/1.0/Data/shopsort.xml",diconv(array2xml($shopsorts, 1),"UTF-8", $_G['charset']."//IGNORE"));
	foreach($shopsorts as $k=>$v ){
		if($v['sortupid']==0){
			$sorts[$v['shopsortid']] = $v;
			foreach($shopsorts as $k1=>$v1 ){
				if($v1['sortupid']==$v['shopsortid']){
					$sorts[$v['shopsortid']]['subsort'][] = $v1;
				}
			}
			$sorts[$v['shopsortid']]['subsort'] = array_sort($sorts[$v['shopsortid']]['subsort'],'displayorder','asc');
		}
	}

	foreach($sorts as $k=>$row ){
		showtablerow('', array('class="td25"','style="width:120px"','class="td23"', '', ''), array(
			(count($row['subsort']) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$k.'\', this)">[+]</a>' : '').(count($row['subsort']) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[shopsortid]\" />"),
			'<input type="text" name="displayordernew['.$row['shopsortid'].']" value="'.$row['displayorder'].'" size="2" />&nbsp;&nbsp;('.count($row['subsort']).')',
			"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[shopsortid]]\" value=\"".$row['sortname']."\">",
			"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[shopsortid]]\" value=\"".$row['sorttitle']."\"><input type=\"hidden\" name=\"upsortid[$row[shopsortid]]\" value=\"0\"><a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, '$row[shopsortid]')\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_subsort')."</a></div>",

		));
			showtagheader('tbody', 'subnav_'.$k,false);
			foreach ($row['subsort']  as $kk => $subrow ){
				showtablerow('', array('class="td25"','style="width:120px"','class="td23"', '', ''), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$subrow[shopsortid]\">",
					"<div class=\"board\"><input type=\"text\" name=\"displayordernew[$subrow[shopsortid]]\" value=\"$subrow[displayorder]\" size=\"2\" /></div>",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$subrow[shopsortid]]\" value=\"".$subrow['sortname']."\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$subrow[shopsortid]]\" value=\"".$subrow['sorttitle']."\"><input type=\"hidden\" name=\"upsortid[$subrow[shopsortid]]\" value=\"".$subrow['sortupid']."\">",
				));
			}
			showtagfooter('tbody');
	}
	echo '<tr><td></td><td colspan="6"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_shopsort').'</a></div></td></tr>';
	showsubmit('submit','submit','del','&nbsp;&nbsp;<a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=export">'.lang('plugin/yiqixueba','export').'</a>');
	showtablefooter();
	showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newname[]" value="" size="15" type="text" class="txt">'],[1, '<input name="newtitle[]" value="" size="15" type="text" class="txt">'],[2,'']],
		[[1, '', 'td25'], [1,'<div class=\"board\"><input name="newdisplayorder[]" value="0" size="3" type="text" class="txt"></div>', 'td25'], [1, '<input name="newsubsortname[]" value="" size="15" type="text" class="txt">'],[1, '<input name="newsubsorttitle[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupsort[]" value="{1}" />'], [2,'']]
	];
</script>
EOT;
}else{
	//版本删除
	foreach( getgpc('delete') as $k=>$v ){
		if($v){
			C::t(GM('shop_shopsort'))->delete($v);
		}
	}
	//原数据提交更新仅一级分类数据
	if(is_array($_GET['namenew'])) {
		foreach($_GET['namenew'] as $k => $v) {
			$v = dhtmlspecialchars(trim($v));
			$titlenew = trim(dhtmlspecialchars($_GET['titlenew'][$k]));
			$displayordernew = intval($_GET['displayordernew'][$k]);
			$upsortid = intval($_GET['upsortid'][$k]);
			if($v && $titlenew){
				$data['sortname'] = $v;
				$data['sorttitle'] = $titlenew;
				$data['displayorder'] = $displayordernew;
				$data['sortupid'] = $upsortid;
				C::t(GM('shop_shopsort'))->update($k,$data);
			}
		}
	}
	//新建一级分类
	if(is_array($_GET['newname'])) {
		foreach($_GET['newname'] as $k => $v) {
			$v = dhtmlspecialchars(trim($v));
			$newtitle = trim(dhtmlspecialchars($_GET['newtitle'][$k]));
			$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
			if($v && $newtitle){
				$data['sortname'] = $v;
				$data['upmokuai'] = 0;
				$data['sorttitle'] = $newtitle;
				$data['sortlevel'] = 1;
				$data['sortupid'] = 0;
				$data['displayorder'] = $newdisplayorder;
				$data['upids'] = '';
				C::t(GM('shop_shopsort'))->insert($data);
			}
		}
	}
	//新建二级分类
	if(is_array($_GET['newsubsortname'])) {
		foreach($_GET['newsubsortname'] as $k => $v) {
			$v = dhtmlspecialchars(trim($v));
			$newsubsorttitle = trim(dhtmlspecialchars($_GET['newsubsorttitle'][$k]));
			$newupsort = intval($_GET['newupsort'][$k]);
			$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
			if($v && $newsubsorttitle){
				$data['sortname'] = $v;
				$data['upmokuai'] = 0;
				$data['sorttitle'] = $newsubsorttitle;
				$data['sortlevel'] = 2;
				$data['sortupid'] = $newupsort;
				$data['displayorder'] = $newdisplayorder;
				$data['upids'] = '';
				C::t(GM('shop_shopsort'))->insert($data);
			}
		}
	}
	cpmsg(lang('plugin/yiqixueba_server', 'sort_edit_succeed'), 'action='.$this_page, 'succeed');
}

