<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
require_once libfile('class/xml');
$shopsorts = $shopsorts_temp = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/goodssort.xml"));

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','shopsort_list_tips'));
	showformheader($this_page);
	showtableheader(lang('plugin/yiqixueba','shopsort_list'));
	showsubtitle(array('', lang('plugin/yiqixueba','displayorder'),lang('plugin/yiqixueba','shopsortname'),lang('plugin/yiqixueba','shopsorttitle'),  ''));


	foreach($shopsorts as $k=>$v ){
		if($v['sortupid']==''){
			$sorts[$v['sortname']] = $v;
			foreach($shopsorts as $k1=>$v1 ){
				if($v1['sortupid']==$v['sortname']){
					$sorts[$v['sortname']]['subsort'][] = $v1;
				}
			}
			$sorts[$v['sortname']]['subsort'] = array_sort($sorts[$v['sortname']]['subsort'],'displayorder','asc');
		}
	}

	foreach($sorts as $k=>$row ){
		showtablerow('', array('class="td25"','style="width:120px"','class="td23"', '', ''), array(
			(count($row['subsort']) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$k.'\', this)">[+]</a>' : '').(count($row['subsort']) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[sortname]\" />"),
			'<input type="text" name="displayordernew['.$row['sortname'].']" value="'.$row['displayorder'].'" size="2" />&nbsp;&nbsp;('.count($row['subsort']).')',
			"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[sortname]]\" value=\"".$row['sortname']."\">",
			"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[sortname]]\" value=\"".$row['sorttitle']."\"><input type=\"hidden\" name=\"upsortid[$row[sortname]]\" value=\"\"><a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, '$row[sortname]')\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_subsort')."</a></div>",

		));
			showtagheader('tbody', 'subnav_'.$k,false);
			foreach ($row['subsort']  as $kk => $subrow ){
				showtablerow('', array('class="td25"','style="width:120px"','class="td23"', '', ''), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$subrow[sortname]\">",
					"<div class=\"board\"><input type=\"text\" name=\"displayordernew[$subrow[sortname]]\" value=\"$subrow[displayorder]\" size=\"2\" /></div>",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$subrow[sortname]]\" value=\"".$subrow['sortname']."\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$subrow[sortname]]\" value=\"".$subrow['sorttitle']."\"><input type=\"hidden\" name=\"upsortid[$subrow[sortname]]\" value=\"".$row['sortname']."\">",
				));
			}
			showtagfooter('tbody');
	}
	echo '<tr><td></td><td colspan="6"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_shopsort').'</a></div></td></tr>';
	showsubmit('submit','submit','del');
	showtablefooter();
	showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newsubsortname[]" value="" size="15" type="text" class="txt">'],[1, '<input name="newsubsorttitle[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupsort[]" value="" />'],[2,'']],
		[[1, '', 'td25'], [1,'<div class=\"board\"><input name="newdisplayorder[]" value="0" size="3" type="text" class="txt"></div>', 'td25'], [1, '<input name="newsubsortname[]" value="" size="15" type="text" class="txt">'],[1, '<input name="newsubsorttitle[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupsort[]" value="{1}" />'], [2,'']]
	];
</script>
EOT;
}else{
		//原数据提交更新仅一级分类数据
		if(is_array($_GET['namenew'])) {
			foreach($_GET['namenew'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$titlenew = trim(dhtmlspecialchars($_GET['titlenew'][$k]));
				$displayordernew = intval($_GET['displayordernew'][$k]);
				$upsortid = trim($_GET['upsortid'][$k]);
				if($v && $titlenew){
					$shopsorts_temp[$v]['sortname'] = $v;
					$shopsorts_temp[$v]['sorttitle'] = $titlenew;
					$shopsorts_temp[$v]['sortupid'] = $upsortid;
					$shopsorts_temp[$v]['displayorder'] = $displayordernew;
				}
			}
		}
		//新建二级分类
		if(is_array($_GET['newsubsortname'])) {
			foreach($_GET['newsubsortname'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newsubsorttitle = trim(dhtmlspecialchars($_GET['newsubsorttitle'][$k]));
				$newupsort = trim($_GET['newupsort'][$k]);
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				if($v && $newsubsorttitle){
					$shopsorts_temp[$v]['sortname'] = $v;
					$shopsorts_temp[$v]['sorttitle'] = $newsubsorttitle;
					$shopsorts_temp[$v]['sortupid'] = $newupsort;
					$shopsorts_temp[$v]['displayorder'] = $newdisplayorder;
				}
			}
		}
		//版本删除
		foreach( getgpc('delete') as $k=>$v ){
			if($v){
				unset($shopsorts_temp[$v]);
			}
		}
		if($shopsorts_temp != $shopsorts){
			file_put_contents (MOKUAI_DIR."/server/1.0/Data/goodssort.xml",diconv(array2xml($shopsorts_temp, 1),"UTF-8", $_G['charset']."//IGNORE"));

		}
	cpmsg(lang('plugin/yiqixueba_server', 'sort_edit_succeed'), 'action='.$this_page, 'succeed');
}
