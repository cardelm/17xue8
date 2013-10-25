<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('mokuailist','mokuaiedit','currentver','pagelist','pluginlang','mokuainode');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

//模块信息读取
require_once libfile('class/xml');
$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
$mokuais = array_sort($mokuais,'displayorder','desc');
$biaoshi = getgpc('biaoshi');
$version = getgpc('version');
$mokuai_info = $mokuais[$biaoshi]['version'][$version];

//人性化记住上次编辑的版本情况
if(getcookie('debugmokuai')){
	$debugmks = explode(",",getcookie('debugmokuai'));
}


if($subop == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','server_mokuai_list_tips'));
		showformheader($this_page.'&subop=mokuailist');
		showtableheader(lang('plugin/yiqixueba','server_mokuai_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','mokuai_name'),lang('plugin/yiqixueba','mokuai_description'),'','',lang('plugin/yiqixueba','status')));
		foreach($mokuais as $mk=>$row ){
			showtablerow('', array('class="td25"', 'class="td25"', 'style="width:360px"', 'style="width:45px"','','class="td25"'), array(
				(is_array($row['version']) ? '<a href="javascript:;" class="right" onclick="toggle_group(\'subnav_'.$mk.'\', this)">['.(in_array($mk,$debugmks)? '-' : '+').']</a>' : '').(is_array($row['version']) ? '<input type="checkbox" class="checkbox" value="" disabled="disabled" />' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$mk\" />"),

				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[]\" value=\"$row[displayorder]\">",

				"<div><input type=\"text\" class=\"txt\" size=\"15\" name=\"biaoshinew[]\" value=\"".$mk."\"  readonly=\"readonly\"><input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[]\" value=\"".($row['name'])."\">"."<a href=\"###\" onclick=\"addrowdirect=1;addrow(this, 1, '$mk')\" class=\"addchildboard\">".lang('plugin/yiqixueba','add_ver')."</a></div>",

				"",

				$row['currentversion'] ? '<span class="bold">'.$row['name'].'-V'.$row['currentversion'].'</span>  <span class="sml">('.str_replace("yiqixueba_","",$mk).')</span>':'',

				"",
			));
			showtagheader('tbody', 'subnav_'.$mk, in_array($mk,$debugmks)? true : false);
			foreach ($row['version']  as $kk => $subrow ){
				$ico = '';
				if($subrow['ico']!='') {
					$ico = str_replace('{STATICURL}', STATICURL, $subrow['ico']);
					if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
						$ico = $_G['setting']['attachurl'].'common/'.$subrow['ico'].'?'.random(6);
					}
				}
				$op_text = ($kk != $row['currentversion'] && $subrow['available'] ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=currentver&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','version')."</a>" : '<span class="bold">'.lang('plugin/yiqixueba','version').'</span>')."&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&ptype=source&subop=pagelist&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','pagelist')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaisetting&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','mokuaisetting')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuainode&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','mokuainode')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;";
				$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaimake&biaoshi=$mk&version=$kk\" >".lang('plugin/yiqixueba','mokuai_make')."</a>&nbsp;&nbsp;";
				$op_text .= '<a href="plugin.php?id=yiqixueba&mokuai=server&submod=mokuaiplay&biaoshi='.$mk.'&version='.$row['currentversion'].'" target="_blank">'.$lang['detail'].'</a>';
				showtablerow('',  array('class="td25"', 'class="td25"', 'style="width:300px"', 'style="width:45px"','','class="td25"'), array(
					'<input class="checkbox" type="checkbox" name="delete[]" value="'.$mk.'_'.$kk.'">',
					"<div class=\"board\">&nbsp;</div>",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"vernamenew[$kk]\" value=\"".$kk."\" readonly=\"readonly\">",
					$ico ?'<img src="'.$ico.'" width="40" height="40" align="left" style="margin-right:5px" />' : '<img src="'.cloudaddons_pluginlogo_url($mk).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
					$op_text.'<br />'.lang('plugin/yiqixueba','price:').$subrow['price'].lang('plugin/yiqixueba','rmb'),
					'<input class="checkbox" type="checkbox"  name="statusnew['.$mk.'_'.$kk.']" value="1" '.($subrow['available'] ? ' checked="checked"' : '').' />',
					));
			}
			showtagfooter('tbody');

		}
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'], [1, '<input name="newbiaoshi[]" value="" size="15" type="text" class="txt"><input name="newname[]" value="" size="15" type="text" class="txt">'],[3,'']],
		[[1, '', 'td25'], [1,'<div class=\"board\">&nbsp;</div>', 'td25'], [1, '<input name="newverbiaoshi[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newupbiaoshi[]" value="{1}" />'], [3,'']]
	];
</script>
EOT;
	}else{
		//原数据提交更新仅模块数据
		if(is_array($_GET['biaoshinew'])) {
			foreach($_GET['biaoshinew'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$namenew = trim(dhtmlspecialchars($_GET['namenew'][$k]));
				$displayordernew = intval($_GET['displayordernew'][$k]);
				if($v && $namenew){
					$mokuais[$v]['name'] = $namenew;
					$mokuais[$v]['displayorder'] = $displayordernew;
				}
			}
		}
		//版本状态更新
		if(is_array($_GET['statusnew'])) {
			$statusnew = $_GET['statusnew'];
			foreach($mokuais as $k => $v) {
				foreach ( $v['version'] as $k1 => $v1 ){
					$mokuais[$k]['version'][$k1]['available'] = 0;
					if($statusnew[$k.'_'.$k1] == 1){
						$mokuais[$k]['version'][$k1]['available'] = 1;
					}
				}
			}
		}
		//新建模块
		if(is_array($_GET['newbiaoshi'])) {
			foreach($_GET['newbiaoshi'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newname = trim(dhtmlspecialchars($_GET['newname'][$k]));
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				if($v && $newname && !in_array($v,array_keys($mokuais))){
					dmkdir(MOKUAI_DIR.'/'.$v);
					$mokuais[$v]['name'] = $newname;
					$mokuais[$v]['displayorder'] = $newdisplayorder;
				}
			}
		}
		//新建版本
		if(is_array($_GET['newverbiaoshi'])) {
			foreach($_GET['newverbiaoshi'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newupbiaoshi = trim(dhtmlspecialchars($_GET['newupbiaoshi'][$k]));
				$debugmokuai_temp[$newupbiaoshi] = 1;
				if($v && !in_array($v,array_keys($mokuais[$newupbiaoshi]['version']))){
					dmkdir(MOKUAI_DIR.'/'.$newupbiaoshi.'/'.$v);
					foreach (array('Controler','Modal','View','Data') as $k1 => $v1 ){
						if(!is_dir(MOKUAI_DIR.'/'.$newupbiaoshi.'/'.$v.'/'.$v1)){
							dmkdir(MOKUAI_DIR.'/'.$newupbiaoshi.'/'.$v.'/'.$v1);
						}
					}
					$mokuais[$newupbiaoshi]['version'][$v]['available'] = 0;
					$mokuaivers = getmokuaivers($newupbiaoshi);
					if(count($mokuaivers)==1){
						$mokuais[$newupbiaoshi]['currentversion'] = $mokuaivers[0];
					}
				}
			}
			//人性化记住操作的模板
			if(is_array(array_keys($debugmokuai_temp))){
				$debugmokuai = array_keys($debugmokuai_temp);
				dsetcookie('debugmokuai',implode(',',$debugmokuai));
			}
		}
		//版本删除
		foreach( getgpc('delete') as $k=>$v ){
			if($v){
				list ($delbiaoshi,$delversion) = explode("_",$v);
				if($delbiaoshi){
					if($delversion){
						unset($mokuais[$delbiaoshi]['version'][$delversion]);
						deldir(MOKUAI_DIR.'/'.$delbiaoshi.'/'.$delversion);
					}else{
						unset($mokuais[$delbiaoshi]);
						deldir(MOKUAI_DIR.'/'.$delbiaoshi);
					}
				}
			}
		}
		$mokuais = array_sort($mokuais,'displayorder','asc');
		file_put_contents (MOKUAI_DIR."/mokuai.xml",diconv(array2xml($mokuais, 1),"UTF-8", $_G['charset']."//IGNORE"));
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_mokuai_succeed'), 'action='.$this_page.'&subop=admincpmenulist', 'succeed');
	}
}elseif($subop == 'mokuaiedit') {
	dsetcookie('debugmokuai',$biaoshi);//人性化
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_mokuai_tips'));
		showformheader($this_page.'&subop=mokuaiedit','enctype');
		showtableheader(lang('plugin/yiqixueba','mokuai_option'));
		$ico = '';
		if($mokuai_info['version'][$version]['ico']!='') {
			$ico = str_replace('{STATICURL}', STATICURL, $mokuai_info['version'][$version]['ico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
				$ico = $_G['setting']['attachurl'].'common/'.$mokuai_info['version'][$version]['ico'].'?'.random(6);
			}
			$icohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$ico.'" width="40" height="40"/>';
		}
		showsetting(lang('plugin/yiqixueba','mokuai_biaoshi'),'mokuai_biaoshi',$biaoshi,'text','readonly',0,lang('plugin/yiqixueba','mokuai_biaoshi_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_name'),'name',$mokuais[$biaoshi]['name'],'text','readonly',0,lang('plugin/yiqixueba','mokuai_name_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_version'),'version',$version,'text','readonly',0,lang('plugin/yiqixueba','mokuai_version_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_price'),'price',$mokuai_info['price'],'text','',0,lang('plugin/yiqixueba','mokuai_price_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_description'),'description',$mokuai_info['description'],'textarea','',0,lang('plugin/yiqixueba','mokuai_description_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','mokuai_ico'),'ico',$mokuai_info['ico'],'filetext','',0,lang('plugin/yiqixueba','mokuai_ico_comment').$icohtml,'','',true);
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','mokuaiinformation').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="mokuaiinformation" style="width:700px;height:200px;visibility:hidden;">'.$mokuai_info['mokuaiinformation'].'</textarea>';
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
		$mokuai_newtag	= trim($_GET['newtag']);

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
	dsetcookie('debugmokuai',$biaoshi);//人性化
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
}elseif ($subop == 'mokuainode'){
	dsetcookie('debugmokuai',$biaoshi);//人性化
	$nodetype = getgpc('nodetype');
	$nodexml_file = MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/node.xml';
	if(!file_exists($nodexml_file)){
		$nodefile = getmod($biaoshi,$version);
		foreach($nodefile as $mk=>$row ){
			if(!in_array($row,array('function','api','ajax'))){
				$nodes[$row]['title'] = '';
				$nodes[$row]['displayorder'] = '';
				$nodes[$row]['menu'] = '';
			}
		}
		file_put_contents ($nodexml_file,diconv(array2xml($nodes, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}
	$nodes = xml2array(file_get_contents($nodexml_file));
	$nodetypes = array('admincp','member','yiqixueba','ajax','api','hook');
	$nodetype = in_array($nodetype,$nodetypes) ? $nodetype : $nodetypes[0];
	foreach($nodetypes as $k=>$v ){
		$nodetype_link	.= '&nbsp;&nbsp;'.($nodetype == $v ? '<span style="color:#000000;">'.lang('plugin/yiqixueba','node_'.$v).'</span>' :'<a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuainode&biaoshi='.$biaoshi.'&version='.$version.'&nodetype='.$v.'">'.lang('plugin/yiqixueba','node_'.$v).'</a>');
	}
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','mokuai_node_tips'));
		showformheader($this_page.'&subop=mokuainode&biaoshi='.$biaoshi.'&version='.$version.'&nodetype='.$nodetype);
		showtableheader();
		showtablerow('',array('class="td25"',''),array(lang('plugin/yiqixueba','current_mokuai'),'<span class="bold">'.$mokuais[$biaoshi]['name'].'-V'.$version.'</span>'));
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','mokuai_node').$nodetype_link);
		if(in_array($nodetype,array('admincp','member','yiqixueba'))){
			$subtitleclass = array('class="td25"','class="td25"', 'style="width:120px"','style="width:120px"','style="width:60px"','');
			showsubtitle(array('', lang('plugin/yiqixueba','node_menu_order'),lang('plugin/yiqixueba','node_name'),lang('plugin/yiqixueba','node_title'),lang('plugin/yiqixueba','node_menu'),''),'header',$subtitleclass);
		}
		foreach($nodes as $mk=>$row ){
			list($nt,$nn) = explode("_",$mk);
			if($nt == $nodetype && in_array($nodetype,array('admincp','member','yiqixueba')) && $nn){
				showtablerow('', $subtitleclass, array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$mk\" />",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$mk]\" value=\"".intval($row['displayorder'])."\">",
					$nn,
					"<input class=\"txt\" type=\"text\" name=\"titlenew[$mk]\" value=\"".$row['title']."\" />",
					"<input class=\"checkbox\" type=\"checkbox\" name=\"menunew[$mk]\" value=\"1\" ".($row['menu']?' checked="checked"':'')."/>",
					'',
				));
			}
		}
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'],[1, '<input name="newnode[]" value="" size="15" type="text">'],[1, '<input name="newtitle[]" value="" size="15" type="text">'],[1, '<input name="newmenu[]" value="1" type="checkbox">'],[1,'']],
	];
</script>
EOT;
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_node').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		//原数据提交更新仅节点数据
		if(is_array($_GET['titlenew'])) {
			foreach($_GET['titlenew'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$nodes[$k]['title'] = $v;
				$nodes[$k]['displayorder'] = intval($_GET['displayordernew'][$k]);
				$nodes[$k]['menu'] = intval($_GET['menunew'][$k]);
			}
		}
		//新建模块
		if(is_array($_GET['newnode'])) {
			foreach($_GET['newnode'] as $k => $v) {
				$v = dhtmlspecialchars(trim($v));
				$newtitle = trim(dhtmlspecialchars($_GET['newtitle'][$k]));
				$newnodefile = MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/Controler/'.$nodetype.'_'.$v.'.php';
				if(!file_exists($newnodefile)){
					file_put_contents($newnodefile,"<?php\n?>");

				}
				$nodes[$nodetype.'_'.$v]['title'] = $newtitle;
				$nodes[$nodetype.'_'.$v]['displayorder'] = intval($_GET['newdisplayorder'][$k]);
				$nodes[$nodetype.'_'.$v]['menu'] = intval($_GET['newmenu'][$k]);
			}
		}
		//版本删除
		foreach( getgpc('delete') as $k=>$v ){
			$newnodefile = MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/Controler/'.$v.'.php';
			@unlink($newnodefile);
			unset($nodes[$v]);
		}
		$nodes = array_sort($nodes,'displayorder','asc');
		//dump($nodes);
		file_put_contents ($nodexml_file,diconv(array2xml($nodes, 1),"UTF-8", $_G['charset']."//IGNORE"));
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_node_succeed'), 'action='.$this_page.'&&subop=mokuainode&biaoshi='.$biaoshi.'&version='.$version.'&nodetype='.$nodetype, 'succeed');
	}
}

?>