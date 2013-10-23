<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('mokuailist','mokuaiedit','currentver','pagelist','pluginlang');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

//模块信息读取
require_once libfile('class/xml');
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
$biaoshi = getgpc('biaoshi');
$version = getgpc('version');
foreach($mokuais as $k=>$v ){
	if($v['biaoshi'] == $biaoshi && $v['currentversion'] == $version){
		$mokuai_info = $v;
	}
}

foreach(getmokuais() as $k=>$v ){
	$data[$v]['biaoshi'] = $v;
	$mokuaivers = getmokuaivers($v);
	foreach($mokuaivers as $k1=>$v1 ){
		$data[$v]['version'][$v1] = array(
			'name' => $v,
			'biaoshi' => $v1,
			'price' => 0,
			'description' => 'jj',
			'ico' => $ico,
			'mokuaiinformation' => 'xq',
			'available' => 1,
			'updatetime' => '',
			'createtime' => filemtime(MOKUAI_DIR.'/'.$v.'/'.$v1)
		);
		$versions[] = $v1;
	}
	if(count($mokuaivers)==1){
		$data[$v]['currentversion'] = $mokuaivers[0];
	}
}

//$mokuai_xml = array2xml($data, 1);
//file_put_contents (MOKUAI_DIR."/mokuai.xml",diconv($mokuai_xml,"UTF-8", $_G['charset']."//IGNORE"));




if($subop == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','server_mokuai_list_tips'));
		showformheader($this_page.'&subop=mokuailist');
		showtableheader(lang('plugin/yiqixueba','server_mokuai_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','mokuai_name'),lang('plugin/yiqixueba','mokuai_description')));
		foreach($mokuais as $mk=>$row ){
			showtablerow('', array('class="td25"', 'class="td25"', 'style="width:360px"', 'style="width:45px"','','class="td25"'), array(

				($row['version'] ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$row['biaoshi'].'\', this)">[+]</a>' : '').($row['version'] ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[biaoshi]\" />"),

				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$row[biaoshi]]\" value=\"$row[displayorder]\">",

				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$row[biaoshi]]\" value=\"".$row['biaoshi']."\"  readonly=\"readonly\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$row[biaoshi]]\" value=\"".($row['name'])."\">"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, $row[biaoshi])\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_ver')."</a></div>",

				"",

				'<input type="hidden" name="upidnew['.$row['biaoshi'].']" value="0" />',

				"<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$row[biaoshi]]\" value=\"1\" ".($row['status'] ? ' checked="checked"' : '')." />",
			));
			showtagheader('tbody', 'subnav_'.$row['biaoshi'], false);
			foreach ($row['version']  as $kk => $subrow ){
				$ico = '';
				if($subrow['ico']!='') {
					$ico = str_replace('{STATICURL}', STATICURL, $subrow['ico']);
					if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
						$ico = $_G['setting']['attachurl'].'common/'.$subrow['ico'].'?'.random(6);
					}
				}
				$op_text = "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&edittype=ver&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','version')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&ptype=source&subop=pagelist&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','pagelist')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaisetting&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','mokuaisetting')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaimake&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','mokuai_make')."</a>&nbsp;&nbsp;";
				$op_text .= '<a href="plugin.php?id=yiqixueba&mokuai=server&submod=mokuaiplay&biaoshi='.$row['biaoshi'].'&version='.$row['currentversion'].'" target="_blank">'.$lang['detail'].'</a>';
				showtablerow('',  array('class="td25"', 'class="td25"', 'style="width:360px"', 'style="width:45px"','','class="td25"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$subrow[biaoshi]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$subrow[biaoshi]]\" value=\"$subrow[displayorder]\">",
					"<div class=\"board\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$subrow[biaoshi]]\" value=\"".$subrow['biaoshi']."\" readonly=\"readonly\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"titlenew[$subrow[biaoshi]]\" value=\"".$subrow['name']."\"></div>",
					$ico ?'<img src="'.$ico.'" width="40" height="40" align="left" style="margin-right:5px" />' : '<img src="'.cloudaddons_pluginlogo_url($row['biaoshi']).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
					$op_text.'<br />'.lang('plugin/yiqixueba','price:').$subrow['price'].lang('plugin/yiqixueba','rmb').'&nbsp;:&nbsp;'.lang('plugin/yiqixueba','status')."<input class=\"checkbox\" type=\"checkbox\"  name=\"statusnew[$subrow[biaoshi]]\" value=\"1\" ".($subrow['status'] ? ' checked="checked"' : '')." />",
					));
			}
			showtagfooter('tbody');

		}
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newname[]" value="" size="15" type="text" class="txt"><input name="newtitle[]" value="" size="15" type="text" class="txt">'],[1,'<input type="hidden" name="newupid[]" value="0" />']],
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<div class=\"board\"><input name="newname[]" value="" size="15" type="text" class="txt"></div>'], [1,'<input name="newtitle[]" value="" size="15" type="text" class="txt">'], [5, '$modlist<input type="hidden" name="newupid[]" value="{1}" />']]
	];
</script>
EOT;
		showtableheader(lang('plugin/yiqixueba','server_mokuai_list'));
		foreach($mokuais as $mk=>$row ){
			$ver_text = '';
			$ver_array = $del_array = $vers_array = array();
			foreach ($row['version']  as $vk => $row1 ){
				$ver_array[] = "<input class=\"checkbox\" type=\"checkbox\" name=\"vernew[".$row['biaoshi']."_".$row1['biaoshi']."]\" value=\"1\" ".($row1['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;".($row['currentversion'] == $vk ? '<span class="bold">V'.$row1['biaoshi'].'</span>' : '<a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=currentver&biaoshi='.$row['biaoshi'].'&version='.$row1['biaoshi'].'" >V'.$row1['biaoshi'].'</a>');
				$del_array[] = "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".$row['biaoshi']."_".$row1['biaoshi']."\">&nbsp;&nbsp;".($row['currentversion'] == $vk ? '<span class="bold">V'.$row1['biaoshi'].'</span>' : '<a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=currentver&biaoshi='.$row['biaoshi'].'&version='.$row1['biaoshi'].'" >V'.$row1['biaoshi'].'</a>');
				$vers_array[] = ($row['currentversion'] == $vk ? '<span class="bold">V'.$row1['biaoshi'].'</span>' : '<a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=currentver&biaoshi='.$row['biaoshi'].'&version='.$row1['biaoshi'].'" >V'.$row1['biaoshi'].'</a>').'&nbsp;:&nbsp;'.lang('plugin/yiqixueba','status')."<input class=\"checkbox\" type=\"checkbox\" name=\"vernew[".$row['biaoshi']."_".$row1['biaoshi']."]\" value=\"1\" ".($row1['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;".lang('plugin/yiqixueba','delete')."<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".$row['biaoshi']."_".$row1['biaoshi']."\">&nbsp;&nbsp;".lang('plugin/yiqixueba','price:').$row1['price'].lang('plugin/yiqixueba','rmb');
			}
			$ver_text = implode("&nbsp;&nbsp;|&nbsp;&nbsp;",$ver_array);
			$del_text = implode("&nbsp;&nbsp;|&nbsp;&nbsp;",$del_array);
			$vers_text = implode("<br />",$vers_array);
			$ico = '';
			if($row['ico']!='') {
				$ico = str_replace('{STATICURL}', STATICURL, $row['ico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
					$ico = $_G['setting']['attachurl'].'common/'.$row['ico'].'?'.random(6);
				}
			}

			$op_text = "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&edittype=ver&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','version')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&ptype=source&subop=pagelist&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','pagelist')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaisetting&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','mokuaisetting')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaimake&biaoshi=$row[biaoshi]&version=$row[currentversion]\" >".lang('plugin/yiqixueba','mokuai_make')."</a>&nbsp;&nbsp;";
			$op_text .= '<a href="plugin.php?id=yiqixueba&mokuai=server&submod=mokuaiplay&biaoshi='.$row['biaoshi'].'&version='.$row['currentversion'].'" target="_blank">'.$lang['detail'].'</a>';
			showtablerow('', array('style="width:45px"', 'style="width:320px"', 'valign="top"'), array(
				$ico ?'<img src="'.$ico.'" width="40" height="40" align="left" style="margin-right:5px" />' : '<img src="'.cloudaddons_pluginlogo_url($row['biaoshi']).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
				'<span class="bold">'.$row['version'][$row['currentversion']]['name'].'-V'.$row['currentversion'].'</span>  <span class="sml">('.str_replace("yiqixueba_","",$row['biaoshi']).')</span><br />'.lang('plugin/yiqixueba','displayorder')."<input type=\"text\" name=\"newdisplayorder[".$row['biaoshi']."]\" value=\"".intval($row['displayorder'])."\" size=\"2\">".'<br />'.$op_text,
				$row['description'].$vers_text,
			));
		}
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr">'.lang('plugin/yiqixueba','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		//C::t(GM('main_mokuai'))->update_all(array('available'=>0));
		//dump($_GET);
		foreach( getgpc('delete') as $k=>$v ){
			if($v){
				list ($delbiaoshi,$delversion) = explode("_",$v);
				dump($v);
			}
		}
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				//C::t(GM('main_mokuai'))->update($k,array('available'=>1));
			}
		}
		foreach( getgpc('statusnew') as $k=>$v ){
			if($v){
				//C::t(GM('main_mokuai'))->update($k,array('available'=>1));
			}
		}
		foreach(getgpc('newdisplayorder') as $k => $v ){
			$mokuais[$k]['displayorder'] = intval($v);
		}
		$mokuais =  array_sort($mokuais,'displayorder');
		//file_put_contents (MOKUAI_DIR."/mokuai.xml",diconv(array2xml($mokuais, 1),"UTF-8", $_G['charset']."//IGNORE"));
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_mokuai_succeed'), 'action='.$this_page.'&subop=admincpmenulist', 'succeed');
	}

}elseif($subop == 'mokuaiedit') {
	$edittype = getgpc('edittype');
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba',$biaoshi && $version ?'edit_mokuai_tips':'add_mokuai_tips'));
		showformheader($this_page.'&subop=mokuaiedit','enctype');
		showtableheader(lang('plugin/yiqixueba','mokuai_option'));
		$biaoshi && $version && !$edittype ? showhiddenfields(array('biaoshi'=>$biaoshi,'version'=>$version)) : '';
		$edittype ? showhiddenfields(array('edittype'=>$edittype)) : '';
		$ico = '';
		if($mokuai_info['version'][$version]['ico']!='') {
			$ico = str_replace('{STATICURL}', STATICURL, $mokuai_info['version'][$version]['ico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
				$ico = $_G['setting']['attachurl'].'common/'.$mokuai_info['version'][$version]['ico'].'?'.random(6);
			}
			$icohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$ico.'" width="40" height="40"/>';
		}
		if($edittype){
			showsetting(lang('plugin/yiqixueba','mokuai_version'),'version','','text','',0,lang('plugin/yiqixueba','mokuai_version_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_biaoshi'),'mokuai_biaoshi',$mokuai_info['biaoshi'],'text','readonly',0,lang('plugin/yiqixueba','mokuai_biaoshi_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_name'),'name',$mokuai_info['version'][$version]['name'],'text','',0,lang('plugin/yiqixueba','mokuai_name_comment'),'','',true);
		}else{
			showsetting(lang('plugin/yiqixueba','mokuai_biaoshi'),'mokuai_biaoshi',$mokuai_info['biaoshi'],'text',$biaoshi && $version ? 'readonly' : '',0,lang('plugin/yiqixueba','mokuai_biaoshi_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_name'),'name',$mokuai_info['version'][$version]['name'],'text','',0,lang('plugin/yiqixueba','mokuai_name_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_version'),'version',$mokuai_info['currentversion'],'text',$biaoshi && $version ? 'readonly' : '',0,lang('plugin/yiqixueba','mokuai_version_comment'),'','',true);
		}
		showsetting(lang('plugin/yiqixueba','mokuai_price'),'price',$mokuai_info['version'][$version]['price'],'text','',0,lang('plugin/yiqixueba','mokuai_price_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_description'),'description',$mokuai_info['version'][$version]['description'],'textarea','',0,lang('plugin/yiqixueba','mokuai_description_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_ico'),'ico',$mokuai_info['version'][$version]['ico'],'filetext','',0,lang('plugin/yiqixueba','mokuai_ico_comment').$icohtml,'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','mokuaiinformation').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="mokuaiinformation" style="width:700px;height:200px;visibility:hidden;">'.$mokuai_info['version'][$version]['mokuaiinformation'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="mokuaiinformation"]', {
			cssPath : 'source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css',
			uploadJson : 'source/plugin/yiqixueba/template/kindeditor/upload_json.php',
			items : ['source', '|', 'undo', 'redo', '|', 'preview', 'cut', 'copy', 'paste','plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright','justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript','superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/','formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold','italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage','flash', 'media',  'table', 'hr', 'emoticons','pagebreak','anchor', 'link', 'unlink', '|', 'about'],
			afterCreate : function() {
				var self = this;
				K.ctrl(document, 13, function() {
					self.sync();
					K('form[name=cpform]')[0].submit();
				});
				K.ctrl(self.edit.doc, 13, function() {
					self.sync();
					K('form[name=cpform]')[0].submit();
				});
			}
		});
		prettyPrint();
	});
</script>
EOF;
	} else {
		$biaoshi = trim($_GET['mokuai_biaoshi']);
		$version = strip_tags(trim($_GET['version']));
		$mokuai_name	= dhtmlspecialchars(trim($_GET['name']));
		$mokuai_price	= trim($_GET['price']);
		$mokuai_description	= dhtmlspecialchars(trim($_GET['description']));
		$mokuai_information	= trim($_GET['mokuaiinformation']);

		if(!$biaoshi){
			cpmsg(lang('plugin/yiqixueba','mokuai_biaoshi_invalid'), '', 'error');
		}
		if(!$version){
			cpmsg(lang('plugin/yiqixueba','mokuai_version_invalid'), '', 'error');
		}
		if(!$mokuai_name){
			cpmsg(lang('plugin/yiqixueba','mokuai_name_invalid'), '', 'error');
		}
		if(!ispluginkey($biaoshi)) {
			cpmsg(lang('plugin/yiqixueba','mokuai_biaoshi_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['ico']);
		if($_FILES['ico']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['ico'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['ico'])) {
			$valueparse = parse_url(addslashes($_POST['ico']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['ico']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['ico']));
			}
			$ico = '';
		}
		$data = array(
			'name' => $mokuai_name,
			'price' => $mokuai_price,
			'description' => $mokuai_description,
			'ico' => $ico,
			'mokuaiinformation' => $mokuai_information,
		);
		update_mokuai($biaoshi,$version,$data);
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_mokuai_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
	}
}elseif ($subop == 'currentver'){
	$mukauidata['currentversion'] = $version;
	$mokuais[$biaoshi]['currentversion'] = $version;
	file_put_contents (MOKUAI_DIR."/mokuai.xml",diconv(array2xml($mokuais, 1),"UTF-8", $_G['charset']."//IGNORE"));
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','mokuai_qiehuan_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
}elseif ($subop == 'pagelist'){
	$ptype= getgpc('ptype');//页面还是模板
	$pagetype = getgpc('pagetype');//页面前缀
	$mokuaipages = get_mokuaipage($mokuai_info['biaoshi'],$mokuai_info['version'],$ptype);
	if(!submitcheck('submit')) {
		$pagetypes = array();
		foreach ($mokuaipages  as $k => $v ){
			if(stripos($v,'_') && !in_array(substr($v,0,stripos($v,'_')),$pagetypes)){
				$pagetypes[] = substr($v,0,stripos($v,'_'));
			}
		}
		showtips(lang('plugin/yiqixueba','page_list_tips'));
		showformheader($this_page.'&subop=pagelist&mokuaiid='.$mokuaiid);
		showtableheader('search');
		echo '<tr><td>';
		echo lang('plugin/yiqixueba','select_ptype').'&nbsp;&nbsp;<select name="ptype" onchange="ajaxget(\'plugin.php?id=yiqixueba:ajax&ajaxtype=getpagetype&mokuaiid='.$mokuaiid.'&ptype=\'+ this.value, \'pagetype\');">';
		echo '<option value="source" '.($ptype == 'source' ? ' selected' : '').'>'.lang('plugin/yiqixueba','page_source').'</option><option value="template" '.($ptype == 'template' ? ' selected' : '').'>'.lang('plugin/yiqixueba','page_template').'</option><option value="table" '.($ptype == 'table' ? ' selected' : '').'>'.lang('plugin/yiqixueba','page_table').'</option>';
		echo '</select>&nbsp;&nbsp;<span id="pagetype">';
		if($pagetypes){
			echo ''.lang('plugin/yiqixueba','select_pagetype').'&nbsp;&nbsp;<select name="pagetype">';
			echo '<option value="">'.lang('plugin/yiqixueba','select').'</option>';
			foreach($pagetypes as $k=>$v ){
				echo '<option value="'.$v.'" '.($pagetype == $v ? ' selected' : '').'>'.$v.'</option>';
			}
			echo '</select>&nbsp;&nbsp;';
		}
		echo "</span><input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(($ptype == 'source' ? lang('plugin/yiqixueba','page_list') : ($ptype == 'template' ? lang('plugin/yiqixueba','template_list') : lang('plugin/yiqixueba','table_list'))).'&nbsp;&nbsp;'.$mokuai_info['name'].'-'.$mokuai_info['version']);
		showsubtitle(array('',lang('plugin/yiqixueba','page_name'),''));
		$mokuai_pages = $mokuaipages;
		$pk =0;
		foreach($mokuai_pages as $k=>$v ){
			if(!$pagetype && !stripos($v,'_') || $pagetype && $pagetype == substr($v,0,stripos($v,'_'))){
				showtablerow('', array('class="td25"','class="td29"','class="td28"','class="td25"','class="td28"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".$v."\">",
					$v,
					"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=pageedit&mokuaiid=$mokuaiid&ptype=$ptype&pagename=".$v."\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=pluginlang&mokuaiid=$mokuaiid&pagename=".($ptype == 'source' ? 'source' : 'template')."_$v\" >".lang('plugin/yiqixueba','pluginlang')."</a>",
				));
				$pk++;
			}
		}
		showtablerow('', array('class="td25"','class="td29"','class="td28"','class="td25"','class="td28"'), array(
			cplang('add_new'),
			($pagetype ? '<INPUT type="hidden" name="pagetypetext" value="'.$pagetype.'">' : '').'<input type="text" size="20" name="newpagename" value="'.($pagetype ? $pagetype.'_' : '').'">',
			'',
			'',
			'',
		));
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
		$page_name	= dhtmlspecialchars(trim($_GET['newpagename']));
		if(in_array($page_name,$mokuaipages)){
			//cpmsg(lang('plugin/yiqixueba','mokuai_page_invalid'), '', 'error');
		}

		if($_GET['delete']) {
			foreach($_GET['delete'] as $k=>$v ){
				@unlink(DISCUZ_ROOT.'source/plugin/yiqixueba/'.$ptype.'/mokuai/'.$mokuaiid.($ptype=='source'? '/page/' : '/').$v.($ptype=='source'? '.php' : '.htm'));
				//dump((DISCUZ_ROOT.'source/plugin/yiqixueba/'.$ptype.'/mokuai/'.$mokuaiid.'/'.$v.($ptype=='source'? '.php' : '.htm')));
				//dump(file_exists(DISCUZ_ROOT.'source/plugin/yiqixueba/'.$ptype.'/mokuai/'.$mokuaiid.'/page/'.$v.($ptype=='source'? '.php' : '.htm')));
			}
		}
		$pages_t = array();
		if($ptype == 'source' && in_array($pagetype,array('admincp','yiqixueba','member'))){
			$menu_name = getgpc('menunew');
			foreach(getgpc('newdisplayorder') as $k=>$v ){
				if(!in_array($k,$_GET['delete'])){
					$temp_menu_array[$k] = array('name'=>$k,'menu'=>$menu_name[$k],'displayorder'=>$v);
				}
			}
			$temp_menu_array = array_sort($temp_menu_array,'displayorder');
			foreach ($temp_menu_array as $k => $v ){
				$temp_pages[$v['name']] = $pages_t[$v['name']];
				unset($pages_t[$v['name']]);
				//if($temp_pages[$v['name']]){
					$pages_t[$v['name']] = $temp_pages[$v['name']];
				//}
				if($v['menu']){
					$temp_menus[$v['name']] = $v['menu'];
				}
			}
			unset($menus_t[$pagetype]);
			$menus_t[$pagetype] = $temp_menus;
		}

		if(str_replace(trim(getgpc('pagetypetext')).'_',"",$page_name)!=""){
			file_put_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/'.$ptype.'/mokuai/'.$mokuaiid.'/'.($ptype=='source'? 'page/' : '').$page_name.($ptype=='source'? '.php' : '.htm'),$ptype=='source'? "<?php\n?>" :"<!--{template common/header}-->\n\n<!--{subtemplate common/footer}-->");
		}
		$mokuaiid_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/'.$ptype.'/mokuai/'.$mokuaiid.($ptype=='source'? '/page' : '');
		if ($handle = opendir($mokuaiid_dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != "index.html" && substr($file,0,1) != ".") {
					if($ptype == 'source' && !$pages_t[str_replace(".php","",$file)]){
						$pages_t[str_replace(".php","",$file)] = md5($sitekey.$mokuai_info['biaoshi'].$file);
					}
					if($ptype == 'template' && !$temps_t[str_replace(".htm","",$file)]){
						$temps_t[str_replace(".htm","",$file)] = md5($sitekey.$mokuai_info['biaoshi'].$file);
					}
				}
			}
		}
		dump($mokuaiid_dir);
		dump($temps_t);
		//DB::update('yiqixueba_mokuai', array('temps'=>serialize($temps_t),'pages'=>serialize($pages_t),'menus'=>serialize($menus_t)),array('mokuaiid'=>$mokuaiid));
		DB::update('yiqixueba_server_mokuai', array('temps'=>serialize($temps_t),'pages'=>serialize($pages_t),'menus'=>serialize($menus_t)),array('mokuaiid'=>$mokuaiid));
		cpmsg(lang('plugin/yiqixueba','page_edit_succeed'), 'action='.$this_page.'&subop=pagelist&mokuaiid='.$mokuaiid.'&mokuaiid='.$mokuaiid.'&pagetype='.$pagetype, 'succeed');
	}
}elseif ($subop == 'pluginlang'){
	$pagename = getgpc('pagename');
	list($page_type) = explode("_",$pagename);
	$mokuai_lang_file = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$mokuai_info['biaoshi'].'/'.$mokuai_info['version'].'/lang.php';
	if(file_exists($mokuai_lang_file)){
		require($mokuai_lang_file);
	}
	$xml_file = DISCUZ_ROOT.'source/plugin/yiqixueba/discuz_plugin_yiqixueba.xml';
	if(file_exists($xml_file)){
		require_once libfile('class/xml');
		$temp_lang = xml2array(file_get_contents($xml_file));
	}
	//dump($temp_lang['Data']['language']);
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','pluginlang_tips'));
		showformheader($this_page.'&subop=pluginlang');
		showtableheader('search');
		$pagenames = get_mokuaipage($mokuai_info['biaoshi'],$mokuai_info['version'],$page_type);
		$pagename = $pagename ? $pagename : $pagenames[0];
		echo '<tr><td>';
		echo '<select name="pagename">';
		foreach($pagenames as $k=>$v ){
			echo '<option value="'.$v.'" '.($pagename == $page_type.'_'.$v ? ' selected' : '').'>'.$v.'</option>';
		}
		echo '</select>';
		echo "&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','pluginlang_edit').'&nbsp;&nbsp;'.$mokuai_info['name'].'-'.$mokuai_info['version']);
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		$mokuai_langs = getmokuailang($mokuai_info['biaoshi'],$mokuai_info['version'],$pagename);
		showhiddenfields(array('page_type'=>$page_type));
		foreach($mokuai_langs[$page_type] as $k=>$v ){
			showsetting('['.$v.']','yiqixueba_lang['.$v.']',$yiqixuebalang[$page_type][$v],'text','',0,'','','',true);
		}
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		$mokuai_lang_key = array('source','template','install','system');
		foreach(getgpc('yiqixueba_lang') as $k=>$v ){
			$yiqixuebalang[getgpc('page_type')][$k] = $v;
		}
		writemokuailang($mokuaiid,$yiqixuebalang);
		updatepluginlang();
		cpmsg(lang('plugin/yiqixueba','mokuai_edit_succeed'), 'action='.$this_page.'&subop=pluginlang&mokuaiid='.$mokuaiid.'&pagename='.($page_type =='source' ? 'source' : 'template').'_'.$pagename, 'succeed');
	}
}

?>