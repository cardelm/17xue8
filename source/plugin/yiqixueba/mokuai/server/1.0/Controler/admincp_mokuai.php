<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('mokuailist','mokuaiedit','currentver','pagelist','pluginlang');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

//模块信息读取
$mokuaiid = getgpc('mokuaiid');
$mokuai_info = C::t(GM('main_mokuai'))->fetch_by_mokuaiid($mokuaiid);

if($subop == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','server_mokuai_list_tips'));
		showformheader($this_page.'&subop=mokuailist');
		showtableheader(lang('plugin/yiqixueba','server_mokuai_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','mokuai_name'),lang('plugin/yiqixueba','mokuai_description'),lang('plugin/yiqixueba','status')));
		$mokuais = C::t(GM('main_mokuai'))->fetch_all_mokuai();
		foreach($mokuais as $mk=>$row ){
			$ver_text = $currenver_text = '';
			$mkvers = C::t(GM('main_mokuai'))->fetch_all_ver($row['biaoshi']);
			$verk = 0;
			foreach ($mkvers  as $vk => $row1 ){
				$ver_text .= ($verk ==0 ? '' :'&nbsp;&nbsp;|&nbsp;&nbsp;')."<input class=\"checkbox\" type=\"checkbox\" name=\"vernew[".$row1['mokuaiid']."]\" value=\"1\" ".($row1['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=currentver&mokuaiid=$row1[mokuaiid]\" >V".$row1['version'].'</a>';
				if($row1['currentversion']){
					$currenver_text = $row1['version'];
				}
				$verk++;
			}
			$currenver_text = $currenver_text ? $currenver_text : $row['version'];
			$currenver_text ? $currenver_text : C::t(GM('main_mokuai'))->update_curver($row['biaoshi'],$currenver_text);
			$ico = '';
			if($row['ico']!='') {
				$ico = str_replace('{STATICURL}', STATICURL, $row['ico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
					$ico = $_G['setting']['attachurl'].'common/'.$row['ico'].'?'.random(6);
				}
			}

			$op_text = "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&edittype=ver&mokuaiid=$row[mokuaiid]\" >".lang('plugin/yiqixueba','version')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&ptype=source&subop=pagelist&mokuaiid=$row[mokuaiid]\" >".lang('plugin/yiqixueba','pagelist')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaisetting&mokuaiid=$row[mokuaiid]\" >".lang('plugin/yiqixueba','mokuaisetting')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaimake&mokuaiid=$row[mokuaiid]\" >".lang('plugin/yiqixueba','mokuai_make')."</a>";
			showtablerow('', array('style="width:45px"', 'style="width:320px"', 'valign="top"', 'align="right" style="width:260px"'), array(
				$ico ?'<img src="'.$ico.'" width="40" height="40" align="left" style="margin-right:5px" />' : '<img src="'.cloudaddons_pluginlogo_url($row['biaoshi']).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
				'(ID:'.$row['mokuaiid'].')<span class="bold">'.$row['name'].'-V'.$currenver_text.'</span>  <span class="sml">('.str_replace("yiqixueba_","",$row['biaoshi']).')</span><br />'.lang('plugin/yiqixueba','version:').$ver_text.'<br />'.lang('plugin/yiqixueba','price:').$row['price'].lang('plugin/yiqixueba','rmb').'&nbsp;&nbsp;<a href="plugin.php?id=yiqixueba&mokuai=server&submod=mokuaiplay&mokuaiid='.$row['mokuaiid'].'" target="_blank">'.$lang['detail'].'</a>',
				$row['description'],
				$op_text."<br /><br />".lang('plugin/yiqixueba','delete')."<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".$row['mokuaiid']."\">&nbsp;&nbsp;".lang('plugin/yiqixueba','status')."<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['mokuaiid']."]\" value=\"1\" ".($row['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;".lang('plugin/yiqixueba','displayorder')."<INPUT type=\"text\" name=\"newdisplayorder[".$row['mokuaiid']."]\" value=\"".$row['displayorder']."\" size=\"2\">",
			));
		}
		echo '<tr><td colspan="1"></td><td colspan="8"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr">'.lang('plugin/yiqixueba','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		C::t(GM('main_mokuai'))->update_all(array('available'=>0));
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				C::t(GM('main_mokuai'))->update($k,array('available'=>1));
			}
		}
		foreach( getgpc('statusnew') as $k=>$v ){
			if($v){
				C::t(GM('main_mokuai'))->update($k,array('available'=>1));
			}
		}
		foreach(getgpc('newdisplayorder') as $k=>$v ){
			if($v){
				C::t(GM('main_mokuai'))->update($k,array('displayorder'=>intval($v)));
			}
		}
		cpmsg(lang('plugin/yiqixueba','edit_mokuai_succeed'), 'action='.$this_page.'&subop=admincpmenulist', 'succeed');
	}

}elseif($subop == 'mokuaiedit') {
	$edittype = getgpc('edittype');
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba',$mokuaiid ?'edit_mokuai_tips':'add_mokuai_tips'));
		showformheader($this_page.'&subop=mokuaiedit','enctype');
		showtableheader(lang('plugin/yiqixueba','mokuai_option'));
		$mokuaiid && !$edittype ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		$edittype ? showhiddenfields(array('edittype'=>$edittype)) : '';
		$ico = '';
		if($mokuai_info['ico']!='') {
			$ico = str_replace('{STATICURL}', STATICURL, $mokuai_info['ico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
				$ico = $_G['setting']['attachurl'].'common/'.$mokuai_info['ico'].'?'.random(6);
			}
			$icohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$ico.'" width="40" height="40"/>';
		}
		if($edittype){
			showsetting(lang('plugin/yiqixueba','mokuai_version'),'version','','text','',0,lang('plugin/yiqixueba','mokuai_version_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_biaoshi'),'mokuai_biaoshi',$mokuai_info['biaoshi'],'text','readonly',0,lang('plugin/yiqixueba','mokuai_biaoshi_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_name'),'name',$mokuai_info['name'],'text','',0,lang('plugin/yiqixueba','mokuai_name_comment'),'','',true);
		}else{
			showsetting(lang('plugin/yiqixueba','mokuai_biaoshi'),'mokuai_biaoshi',$mokuai_info['biaoshi'],'text',$mokuaiid ? 'readonly' : '',0,lang('plugin/yiqixueba','mokuai_biaoshi_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_name'),'name',$mokuai_info['name'],'text','',0,lang('plugin/yiqixueba','mokuai_name_comment'),'','',true);
			showsetting(lang('plugin/yiqixueba','mokuai_version'),'version',$mokuai_info['version'],'text',$mokuaiid ? 'readonly' : '',0,lang('plugin/yiqixueba','mokuai_version_comment'),'','',true);
		}
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
		$mokuai_biaoshi	= trim($_GET['mokuai_biaoshi']);
		$mokuai_name	= dhtmlspecialchars(trim($_GET['name']));
		$mokuai_price	= trim($_GET['price']);
		$mokuai_version	= strip_tags(trim($_GET['version']));
		$mokuai_description	= dhtmlspecialchars(trim($_GET['description']));
		$mokuai_information	= trim($_GET['mokuaiinformation']);

		if(!$mokuai_biaoshi){
			cpmsg(lang('plugin/yiqixueba','mokuai_biaoshi_invalid'), '', 'error');
		}
		if(!$mokuai_name){
			cpmsg(lang('plugin/yiqixueba','mokuai_name_invalid'), '', 'error');
		}
		if(!$mokuai_version){
			cpmsg(lang('plugin/yiqixueba','mokuai_version_invalid'), '', 'error');
		}
		if(!ispluginkey($mokuai_biaoshi)) {
			cpmsg(lang('plugin/yiqixueba','mokuai_biaoshi_invalid'), '', 'error');
		}
		//if(!$mokuaiid&&DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_server_mokuai')." WHERE biaoshi='".$mokuai_biaoshi."' and version = '".$mokuai_version."'")){
			//cpmsg(lang('plugin/yiqixueba','mokuai_biaoshi_invalid'), '', 'error');
		//}
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
			'version' => $mokuai_version,
			'biaoshi' => $mokuai_biaoshi,
			'description' => $mokuai_description,
			'ico' => $ico,
			'mokuaiinformation' => $mokuai_information,
		);
		if($mokuaiid){
			$data['updatetime'] = time();
			C::t(GM('main_mokuai'))->update($mokuaiid, $data);
		}else{
			$data['currentversion'] = C::t(GM('main_mokuai'))->fetch_by_mkcount($mokuai_biaoshi)?0:1;
			$data['createtime'] = time();
			C::t(GM('main_mokuai'))->insert($data);
		}
		make_mokuai($data['biaoshi'],$data['version']);
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','add_mokuai_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
	}
}elseif ($subop == 'currentver'){
	C::t(GM('main_mokuai'))->update_curver($mokuai_info['biaoshi']);
	C::t(GM('main_mokuai'))->update($mokuaiid,array('currentversion'=>1));
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