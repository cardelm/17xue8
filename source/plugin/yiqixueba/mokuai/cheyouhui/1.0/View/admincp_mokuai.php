<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subop = getgpc('subop');
$subops = array('mokuailist','mokuaiedit','pagelist','pageedit','mokuaisetting','settingedit','huanyuan','pluginlang','currentver','mokuaimake');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = $mokuaiid ? DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." WHERE mokuaiid=".$mokuaiid) : array();


if($subop == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],'mokuai_list_tips'));
		showformheader($this_page.'&subop=mokuailist');
		showtableheader(lang('plugin/'.$plugin['identifier'],'mokuai_list'));
		showsubtitle(array('', lang('plugin/'.$plugin['identifier'],'mokuai_name'),lang('plugin/'.$plugin['identifier'],'mokuai_description'),lang('plugin/'.$plugin['identifier'],'mokuai_status')));
		$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." group by identifier order by displayorder asc");
		while($row = DB::fetch($query)) {
			$zhuanhuanen_ids = array();//€€€€€€€€€€€€
			$zhuanhuanen_ids[] = 'yiqixueba_'.$row['identifier'];//€€€€€€€€yiqixuaba_§º€€€€€€€€
			$ver_text = $currenver_text = '';
			$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." WHERE identifier = '".$row['identifier']."' order by createtime asc");
			$verk = 0;
			while($row1 = DB::fetch($query1)) {
				$ver_text .= ($verk ==0 ? '' :'&nbsp;&nbsp;|&nbsp;&nbsp;')."<input class=\"checkbox\" type=\"checkbox\" name=\"vernew[".$row1['mokuaiid']."]\" value=\"1\" ".($row1['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=currentver&mokuaiid=$row1[mokuaiid]\" >".$row1['version'].'</a>';
				if($row1['currentversion']){
					$currenver_text = $row1['version'];
				}
				$verk++;
			}
			$currenver_text =$currenver_text ? $currenver_text : $row['version'];
			$currenver_text ? $currenver_text : DB::update('yiqixueba_server_mokuai', array('currentversion'=>1),array('identifier'=>$row['identifier'],'version'=>$currenver_text));
			$ico = '';
			if($row['ico']!='') {
				$ico = str_replace('{STATICURL}', STATICURL, $row['ico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
					$ico = $_G['setting']['attachurl'].'common/'.$row['ico'].'?'.random(6);
				}
			}
			$modules = $settings = array();
			$settings = dunserialize($row['setting']);
			$modules = dunserialize($row['modules']);
			$op_text = "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=pluginlang&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'pluginlang')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=verlist&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'version')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=pagelist&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'pagelist')."(".count($modules).")</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaisetting&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'mokuaisetting')."(".count($settings).")</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'edit')."</a>&nbsp;&nbsp;";
			$op_text .= "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=mokuaimake&mokuaiid=$row[mokuaiid]\" >".lang('plugin/'.$plugin['identifier'],'mokuai_make')."</a>";
			showtablerow('', array('style="width:45px"', 'style="width:320px"', 'valign="top"', 'align="right" style="width:260px"'), array(
				$ico ?'<img src="'.$ico.'" width="40" height="40" align="left" style="margin-right:5px" />' : '<img src="'.cloudaddons_pluginlogo_url($row['identifier']).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
				'<span class="bold">'.$row['name'].'-'.$currenver_text.'</span>  <span class="sml">('.str_replace("yiqixueba_","",$row['identifier']).')</span><br />'.lang('plugin/yiqixueba','version:').$ver_text.'<br />'.lang('plugin/'.$plugin['identifier'],'price').$row['price'],
				$row['description'],
				$op_text."<br /><br />".lang('plugin/'.$plugin['identifier'],'delete')."<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".$row['mokuaiid']."\">&nbsp;&nbsp;".lang('plugin/'.$plugin['identifier'],'status')."<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['mokuaiid']."]\" value=\"1\" ".($row['available'] > 0 ? 'checked' : '').">&nbsp;&nbsp;".lang('plugin/'.$plugin['identifier'],'displayorder')."<INPUT type=\"text\" name=\"newdisplayorder[".$row['mokuaiid']."]\" value=\"".$row['displayorder']."\" size=\"2\">",
			));
		}
		echo '<tr><td></td><td colspan="3"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=mokuaiedit" class="addtr">'.lang('plugin/'.$plugin['identifier'],'add_mokuai').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
		DB::update('yiqixueba_server_mokuai', array('available'=>0));
		foreach( getgpc('vernew') as $k=>$v ){
			if($v){
				DB::update('yiqixueba_server_mokuai', array('available'=>1),array('mokuaiid'=>$k));
			}
		}
		foreach(getgpc('newdisplayorder') as $k=>$v ){
			DB::update('yiqixueba_server_mokuai', array('displayorder'=>$v),array('mokuaiid'=>$k));
		}
		cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_main_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
	}
}elseif ($subop == 'mokuaiedit'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],$mokuaiid ?'edit_mokuai_tips':'add_mokuai_tips'));
		showformheader($this_page.'&subop=mokuaiedit','enctype');
		showtableheader(lang('plugin/'.$plugin['identifier'],'mokuai_edit'));
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		$ico = '';
		if($mokuai_info['ico']!='') {
			$ico = str_replace('{STATICURL}', STATICURL, $mokuai_info['ico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $ico) && !(($valueparse = parse_url($ico)) && isset($valueparse['host']))) {
				$ico = $_G['setting']['attachurl'].'common/'.$mokuai_info['ico'].'?'.random(6);
			}
			$icohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$ico.'" width="40" height="40"/>';
		}
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_identifier'),'mokuai_identifier',$mokuai_info['identifier'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_identifier_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_name'),'name',$mokuai_info['name'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_name_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_version'),'version',$mokuai_info['version'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_version_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_price'),'price',$mokuai_info['price'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_price_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_description'),'description',$mokuai_info['description'],'textarea','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_description_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuai_edit_ico'),'ico',$mokuai_info['ico'],'filetext','',0,lang('plugin/'.$plugin['identifier'],'mokuai_edit_ico_comment').$icohtml,'','',true);
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<tr class="noborder" ><td colspan="2" class="td27" s="1">'.lang('plugin/yiqixueba','mokuaiinformation').':</td></tr>';
		echo '<tr class="noborder" ><td colspan="2" ><textarea name="mokuaiinformation" style="width:700px;height:200px;visibility:hidden;">'.$mokuai_info['mokuaiinformation'].'</textarea></td></tr>';
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
		if($mokuaiid){
			//make_mokuai($mokuaiid,'');
		}
		$mokuai_identifier	= trim($_GET['mokuai_identifier']);
		$mokuai_name	= dhtmlspecialchars(trim($_GET['name']));
		$mokuai_price	= trim($_GET['price']);
		$mokuai_version	= strip_tags(trim($_GET['version']));
		$mokuai_description	= dhtmlspecialchars(trim($_GET['description']));
		$mokuai_information	= trim($_GET['mokuaiinformation']);

		if(!$mokuai_identifier){
			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_identifier_invalid'), '', 'error');
		}
		if(!$mokuai_name){
			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_name_invalid'), '', 'error');
		}
		if(!$mokuai_version){
			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_version_invalid'), '', 'error');
		}
		if(!ispluginkey($mokuai_identifier)) {
			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_identifier_invalid'), '', 'error');
		}
		if(!$mokuaiid&&DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_server_mokuai')." WHERE identifier='".$mokuai_identifier."' and version = '".$mokuai_version."'")){
			cpmsg(lang('plugin/'.$plugin['identifier'],'mokuai_identifier_invalid'), '', 'error');
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
			'version' => $mokuai_version,
			'identifier' => $mokuai_identifier,
			'description' => $mokuai_description,
			'ico' => $ico,
			'mokuaiinformation' => $mokuai_information,
		);
		if($mokuaiid){
			$data['updatetime'] = time();
			DB::update('yiqixueba_server_mokuai', $data,array('mokuaiid'=>$mokuaiid));
		}else{
			$data['createtime'] = time();
			DB::insert('yiqixueba_server_mokuai', $data);
		}
		cpmsg(lang('plugin/'.$plugin['identifier'],'add_mokuai_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
	}
}elseif ($subop == 'pluginlang'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],'pluginlang_tips'));
		showformheader($this_page.'&subop=pluginlang');
		showtableheader(lang('plugin/'.$plugin['identifier'],'pluginlang_edit').'&nbsp;&nbsp;'.$mokuai_info['name']);
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuaisetting_identifier'),'mokuaisetting_identifier',$mokuaisetting_info['identifier'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuaisetting_identifier_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuaisetting_name'),'name',$mokuaisetting_info['name'],'text','',0,lang('plugin/'.$plugin['identifier'],'mokuaisetting_name_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuaisetting_type'),array('type',array(array('',lang('plugin/'.$plugin['identifier'],'mokuaisetting_type')),array('admincp','admincp'),array('member','member'),array('index','index'))),$mokuaisetting_info['type'],'select','',0,lang('plugin/'.$plugin['identifier'],'mokuaisetting_type_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'mokuaisetting_description'),'description',$mokuaisetting_info['description'],'textarea','',0,lang('plugin/'.$plugin['identifier'],'mokuaisetting_description_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif ($subop == 'pagelist'){
	if(!submitcheck('submit')) {
		$modules = dunserialize($mokuai_info['modules']);
		showtips(lang('plugin/'.$plugin['identifier'],'page_list_tips'));
		showformheader($this_page.'&subop=pagelist&mokuaiid='.$mokuaiid);
		showtableheader(lang('plugin/'.$plugin['identifier'],'page_list').'&nbsp;&nbsp;'.$mokuai_info['name']);
		showsubtitle(array('', lang('plugin/'.$plugin['identifier'],'page_identifier'),lang('plugin/'.$plugin['identifier'],'page_name'),lang('plugin/'.$plugin['identifier'],'displayorder')));
		foreach($modules as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"','class="td28"', 'class="td25"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"".intval($k)."\">",
				$row['name'],
				$row['menu'],
				"<INPUT type=\"text\" name=\"newdisplayorder[".$k."]\" value=\"".$row['displayorder']."\" size=\"2\">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=pageedit&mokuaiid=$mokuaiid&pageid=".($k+1)."\" >".lang('plugin/'.$plugin['identifier'],'edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="7"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=pageedit&mokuaiid='.$mokuaiid.'&pagetype='.$pagetype.'" class="addtr" >'.lang('plugin/'.$plugin['identifier'],'add_page').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
		$modules = dunserialize($mokuai_info['modules']);
		if(is_array($_GET['newdisplayorder'])) {
			foreach($_GET['newdisplayorder'] as $id => $displayorder) {
				$modules[$id]['displayorder'] =  $displayorder;
			}
		}
		if($_GET['delete']) {
			foreach($_GET['delete'] as $k=>$v ){
				unset($modules[$v]);
			}
		}
		$modules =  array_sort($modules,'displayorder');
		$module = serialize($modules);
		DB::update('yiqixueba_server_mokuai', array('modules'=>$module),array('mokuaiid'=>$mokuaiid));
		cpmsg(lang('plugin/'.$plugin['identifier'],'page_edit_succeed'), 'action='.$this_page.'&subop=pagelist&mokuaiid='.$mokuaiid, 'succeed');
	}
}elseif ($subop == 'pageedit'){
	$pageid = getgpc('pageid');
	$modules = dunserialize($mokuai_info['modules']);
	$page_info = $pageid ? $modules[$pageid-1] : array();
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],$pageid ?'edit_page_tips':'add_page_tips'));
		showformheader($this_page.'&subop=pageedit');
		showtableheader(lang('plugin/'.$plugin['identifier'],'page_edit').'&nbsp;&nbsp;'.$mokuai_info['name']);
		$pageid ? showhiddenfields(array('pageid'=>$pageid)) : '';
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		showsetting(lang('plugin/'.$plugin['identifier'],'page_identifier'),'page_identifier',$page_info['name'],'text','',0,lang('plugin/'.$plugin['identifier'],'page_identifier_comment'),'','',true);
		showsetting(lang('plugin/'.$plugin['identifier'],'page_name'),'name',$page_info['menu'],'text','',0,lang('plugin/'.$plugin['identifier'],'page_name_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$pageid = getgpc('pageid');
		$modules = dunserialize($mokuai_info['modules']);
		$page_identifier	= trim($_GET['page_identifier']);
		$page_name	= dhtmlspecialchars(trim($_GET['name']));
		$page_description	= dhtmlspecialchars(trim($_GET['description']));

		if(!$page_identifier){
			cpmsg(lang('plugin/'.$plugin['identifier'],'page_identifier_invalid'), '', 'error');
		}
		if(!$page_name){
			cpmsg(lang('plugin/'.$plugin['identifier'],'page_name_invalid'), '', 'error');
		}
		if(!ispluginkey($page_identifier)) {
			cpmsg(lang('plugin/'.$plugin['identifier'],'page_identifier_invalid'), '', 'error');
		}
		$data = array(
			'name' => $page_identifier,
			'menu' => $page_name,
			'url' => '',
			'type' => 3,
			'adminid' => 1,
			'displayorder' => $modules[$pageid-1]['displayorder'],
			'navtitle' => '',
			'navicon' => '',
			'navsubname' => '',
			'navsuburl' => '',
		);
		//$mokuai_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$mokuaiid;
		$mokuai_dir = 'C:\GitHub\yiqixueba\server\source/plugin/yiqixueba/mokuai/'.$mokuaiid;
		if(!is_dir($mokuai_dir)){
			dmkdir($mokuai_dir);
		}
		if(!is_dir($mokuai_dir.'/page')){
			dmkdir($mokuai_dir.'/page');
		}
		$page_file = $mokuai_dir.'/page/admincp_'.$page_identifier.'.php';
		if(!file_exists($page_file)){
			$file_text = "<?php\n?>";
			file_put_contents($page_file,$file_text);
		}
		if($pageid){
			$modules[$pageid-1] = $data;
		}else{
			$modules[] = $data;
		}
		$modules =  array_sort($modules,'displayorder');
		$module = serialize($modules);
		DB::update('yiqixueba_server_mokuai', array('modules'=>$module),array('mokuaiid'=>$mokuaiid));
		cpmsg(lang('plugin/'.$plugin['identifier'],'page_edit_succeed'), 'action='.$this_page.'&subop=pagelist&mokuaiid='.$mokuaiid, 'succeed');
	}
}elseif ($subop == 'mokuaisetting'){
	if(!submitcheck('submit')) {
		showtips(lang('plugin/'.$plugin['identifier'],'mokuaisetting_tips'));
		showformheader($this_page.'&subop=mokuaisetting&mokuaiid='.$mokuaiid);
		showtableheader('plugins_edit_vars');
		showsubtitle(array('', 'display_order', 'plugins_vars_title', 'plugins_vars_variable', 'plugins_vars_type', ''));
		$settings = dunserialize($mokuai_info['setting']);
		$settings =  array_sort($settings,'displayorder');
		//dump($settings);
		foreach($settings as $k=>$var) {
			$var['type'] = $lang['plugins_edit_vars_type_'. $var['type']];
			$var['title'] .= isset($lang[$var['title']]) ? '<br />'.$lang[$var['title']] : '';
			showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\">",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$k]\" value=\"$var[displayorder]\">",
				$var['title'],
				$var['variable'],
				$var['type'],
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=settingedit&mokuaiid=$mokuaiid&variable=$var[variable]\" class=\"act\">$lang[detail]</a>"
			));
		}
		showtablerow('', array('class="td25"', 'class="td28"'), array(
			cplang('add_new'),
			'<input type="text" class="txt" size="2" name="newdisplayorder" value="0">',
			'<input type="text" class="txt" size="15" name="newtitle">',
			'<input type="text" class="txt" size="15" name="newvariable">',
			'<select name="newtype">
				<option value="number">'.cplang('plugins_edit_vars_type_number').'</option>
				<option value="text" selected>'.cplang('plugins_edit_vars_type_text').'</option>
				<option value="textarea">'.cplang('plugins_edit_vars_type_textarea').'</option>
				<option value="radio">'.cplang('plugins_edit_vars_type_radio').'</option>
				<option value="select">'.cplang('plugins_edit_vars_type_select').'</option>
				<option value="selects">'.cplang('plugins_edit_vars_type_selects').'</option>
				<option value="color">'.cplang('plugins_edit_vars_type_color').'</option>
				<option value="date">'.cplang('plugins_edit_vars_type_date').'</option>
				<option value="datetime">'.cplang('plugins_edit_vars_type_datetime').'</option>
				<option value="forum">'.cplang('plugins_edit_vars_type_forum').'</option>
				<option value="forums">'.cplang('plugins_edit_vars_type_forums').'</option>
				<option value="group">'.cplang('plugins_edit_vars_type_group').'</option>
				<option value="groups">'.cplang('plugins_edit_vars_type_groups').'</option>
				<option value="extcredit">'.cplang('plugins_edit_vars_type_extcredit').'</option>
				<option value="forum_text">'.cplang('plugins_edit_vars_type_forum_text').'</option>
				<option value="forum_textarea">'.cplang('plugins_edit_vars_type_forum_textarea').'</option>
				<option value="forum_radio">'.cplang('plugins_edit_vars_type_forum_radio').'</option>
				<option value="forum_select">'.cplang('plugins_edit_vars_type_forum_select').'</option>
				<option value="group_text">'.cplang('plugins_edit_vars_type_group_text').'</option>
				<option value="group_textarea">'.cplang('plugins_edit_vars_type_group_textarea').'</option>
				<option value="group_radio">'.cplang('plugins_edit_vars_type_group_radio').'</option>
				<option value="group_select">'.cplang('plugins_edit_vars_type_group_select').'</option>
			</seletc>',
			''
		));
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		$settings = dunserialize($mokuai_info['setting']);
		if(is_array($_GET['displayordernew'])) {
			foreach($_GET['displayordernew'] as $id => $displayorder) {
				$settings[$id]['displayorder'] =  $displayorder;
			}
		}
		if($_GET['delete']) {
			foreach($_GET['delete'] as $k=>$v ){
				unset($settings[$v]);
			}
		}
		$newtitle = dhtmlspecialchars(trim($_GET['newtitle']));
		$newvariable = trim($_GET['newvariable']);
		if($newtitle && $newvariable) {
			//if(strlen($newvariable) > 40 || !ispluginkey($newvariable) || C::t('common_pluginvar')->check_variable($pluginid, $newvariable)) {
				//cpmsg('plugins_edit_var_invalid', '', 'error');
			//}
			$settings[] = array(
				'displayorder' => $_GET['newdisplayorder'],
				'title' => $newtitle,
				'variable' => $newvariable,
				'type' => $_GET['newtype'],
				'extra' => '',
			);
		}
		$settings =  array_sort($settings,'displayorder');
		$setting = serialize($settings);
		DB::update('yiqixueba_server_mokuai', array('setting'=>$setting),array('mokuaiid'=>$mokuaiid));
		cpmsg(lang('plugin/'.$plugin['identifier'],'page_edit_succeed'), 'action='.$this_page.'&subop=mokuaisetting&mokuaiid='.$mokuaiid, 'succeed');

	}
}elseif ($subop == 'settingedit'){
	$settings = dunserialize($mokuai_info['setting']);
	foreach($settings as $k=>$v ){
		if($v['variable']==getgpc('variable')){
			$pluginvar = $v;
		}
	}
	if(!submitcheck('submit')) {
		$typeselect = '<select name="typenew" onchange="if(this.value.indexOf(\'select\') != -1) $(\'extra\').style.display=\'\'; else $(\'extra\').style.display=\'none\';">';
		foreach(array('number', 'text', 'radio', 'textarea', 'select', 'selects', 'color', 'date', 'datetime', 'forum', 'forums', 'group', 'groups', 'extcredit',
				'forum_text', 'forum_textarea', 'forum_radio', 'forum_select', 'group_text', 'group_textarea', 'group_radio', 'group_select') as $type) {
			$typeselect .= '<option value="'.$type.'" '.($pluginvar['type'] == $type ? 'selected' : '').'>'.$lang['plugins_edit_vars_type_'.$type].'</option>';
		}
		$typeselect .= '</select>';

		showformheader($this_page."&subop=settingedit&mokuaiid=$mokuaiid&variable=$pluginvar[variable]");
		showtableheader();
		showtitle($lang['plugins_edit_vars'].' - '.$pluginvar['title']);
		showsetting('plugins_edit_vars_title', 'titlenew', $pluginvar['title'], 'text');
		showsetting('plugins_edit_vars_description', 'descriptionnew', $pluginvar['description'], 'textarea');
		showsetting('plugins_edit_vars_type', '', '', $typeselect);
		showsetting('plugins_edit_vars_variable', 'variablenew', $pluginvar['variable'], 'text');
		showtagheader('tbody', 'extra', $pluginvar['type'] == 'select' || $pluginvar['type'] == 'selects');
		showsetting('plugins_edit_vars_extra', 'extranew',  $pluginvar['extra'], 'textarea');
		showtagfooter('tbody');
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		$titlenew	= cutstr(trim($_GET['titlenew']), 25);
		$descriptionnew	= cutstr(trim($_GET['descriptionnew']), 255);
		$variablenew	= trim($_GET['variablenew']);
		$extranew	= trim($_GET['extranew']);

		if(!$titlenew) {
			//cpmsg('plugins_edit_var_title_invalid', '', 'error');
		} elseif($variablenew != $pluginvar['variable']) {
			//if(!$variablenew || strlen($variablenew) > 40 || !ispluginkey($variablenew) || C::t('common_pluginvar')->check_variable($pluginid, $variablenew)) {
				//cpmsg('plugins_edit_vars_invalid', '', 'error');
			//}
		}
		foreach($settings as $k=>$v ){
			if($v['variable']==getgpc('variable')){
				$settings[$k] = array(
					'displayorder' => $v['displayorder'],
					'title' => $titlenew,
					'description' => $descriptionnew,
					'type' => $_GET['typenew'],
					'variable' => $variablenew,
					'extra' => $extranew
				);
			}
		}
		$setting = serialize($settings);
		DB::update('yiqixueba_server_mokuai', array('setting'=>$setting),array('mokuaiid'=>$mokuaiid));

		//updatecache(array('plugin', 'setting', 'styles'));
		//cleartemplatecache();
		cpmsg(lang('plugin/'.$plugin['identifier'],'setting_edit_succeed'), 'action='.$this_page.'&subop=mokuaisetting&mokuaiid='.$mokuaiid, 'succeed');
	}
}elseif ($subop == 'mokuaimake'){
	//$mokuai_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$mokuaiid;
	$mokuai_dir = 'C:\GitHub\yiqixueba\server\source/plugin/yiqixueba/mokuai/'.$mokuaiid;
	if(!is_dir($mokuai_dir)){
		dmkdir($mokuai_dir);
	}
	if(!is_dir($mokuai_dir.'/page')){
		dmkdir($mokuai_dir.'/page');
	}
	$modules = dunserialize($mokuai_info['modules']);
	foreach($modules as $k=>$v ){
		$page_file = $mokuai_dir.'/page/'.$v['type'].'_'.$v['name'].'.php';
		if(!file_exists($page_file)){
			file_put_contents($page_file,"<?php\n?>");
		}
	}
	require_once libfile('class/xml');
	$root = array(
		'Title' => $mokuai_info['name'],
		'Version' => $_G['setting']['version'],
		'Time' => dgmdate(TIMESTAMP, 'Y-m-d H:i'),
		'From' => $_G['setting']['bbname'].' ('.$_G['siteurl'].')',
		'Data' => exportarray($mokuai_info, 1)
	);
	$filename = $mokuai_dir.'/mokuaiinfo.xml';
	$plugin_export = array2xml($root, 1);
	file_put_contents ($filename,$plugin_export);
	cpmsg(lang('plugin/'.$plugin['identifier'],'mokuaimake_edit_succeed'), 'action='.$this_page.'&subop=mokuailist', 'succeed');
}
$db = & DB::object();
$dumpcharset = str_replace('-', '', $_G['charset']);

//dump(sqldumptablestruct(DB::table('yiqixueba_server_mokuai')));
//dump(sqldumptable(DB::table('yiqixueba_server_mokuai')));
function sqldumptablestruct($table) {
	global $_G, $db, $excepttables;

	if(in_array($table, $excepttables)) {
		return;
	}

	$createtable = DB::query("SHOW CREATE TABLE $table", 'SILENT');


	if(!DB::error()) {
		$tabledump = "DROP TABLE IF EXISTS $table;\n";
	} else {
		return '';
	}

	$create = $db->fetch_row($createtable);

	if(strpos($table, '.') !== FALSE) {
		$tablename = substr($table, strpos($table, '.') + 1);
		$create[1] = str_replace("CREATE TABLE $tablename", 'CREATE TABLE '.$table, $create[1]);
	}
	$tabledump .= $create[1];

	if($_GET['sqlcompat'] == 'MYSQL41' && $db->version() < '4.1') {
		$tabledump = preg_replace("/TYPE\=(.+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
	}
	if($db->version() > '4.1' && $_GET['sqlcharset']) {
		$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=".$_GET['sqlcharset'], $tabledump);
	}

	$tablestatus = DB::fetch_first("SHOW TABLE STATUS LIKE '$table'");
	$tabledump .= ($tablestatus['Auto_increment'] ? " AUTO_INCREMENT=$tablestatus[Auto_increment]" : '').";\n\n";
	if($_GET['sqlcompat'] == 'MYSQL40' && $db->version() >= '4.1' && $db->version() < '5.1') {
		if($tablestatus['Auto_increment'] <> '') {
			$temppos = strpos($tabledump, ',');
			$tabledump = substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
		}
		if($tablestatus['Engine'] == 'MEMORY') {
			$tabledump = str_replace('TYPE=MEMORY', 'TYPE=HEAP', $tabledump);
		}
	}
	return $tabledump;
}
function sqldumptable($table, $startfrom = 0, $currsize = 0) {
	global $_G, $db, $startrow, $dumpcharset, $complete, $excepttables;

	$offset = 300;
	$tabledump = '';
	$tablefields = array();

	$query = DB::query("SHOW FULL COLUMNS FROM $table", 'SILENT');
	if(strexists($table, 'adminsessions')) {
		return ;
	} elseif(!$query && DB::errno() == 1146) {
		return;
	} elseif(!$query) {
		$_GET['usehex'] = FALSE;
	} else {
		while($fieldrow = DB::fetch($query)) {
			$tablefields[] = $fieldrow;
		}
	}
//dump($_GET['extendins'] == '0');
	if(!in_array($table, $excepttables)) {
		$tabledumped = 0;
		$numrows = $offset;
		$firstfield = $tablefields[0];

		if($_GET['extendins'] == '0') {
			while($currsize + strlen($tabledump) + 500 < $_GET['sizelimit'] * 1000 && $numrows == $offset) {
				if($firstfield['Extra'] == 'auto_increment') {
					$selectsql = "SELECT * FROM $table WHERE $firstfield[Field] > $startfrom ORDER BY $firstfield[Field] LIMIT $offset";
				} else {
					$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
				}
				$tabledumped = 1;
				$rows = DB::query($selectsql);
				$numfields = $db->num_fields($rows);

				$numrows = DB::num_rows($rows);
				while($row = $db->fetch_row($rows)) {
					$comma = $t = '';
					for($i = 0; $i < $numfields; $i++) {
						$t .= $comma.($_GET['usehex'] && !empty($row[$i]) && (strexists($tablefields[$i]['Type'], 'char') || strexists($tablefields[$i]['Type'], 'text')) ? '0x'.bin2hex($row[$i]) : '\''.mysql_escape_string($row[$i]).'\'');
						$comma = ',';
					}
					if(strlen($t) + $currsize + strlen($tabledump) + 500 < $_GET['sizelimit'] * 1000) {
						if($firstfield['Extra'] == 'auto_increment') {
							$startfrom = $row[0];
						} else {
							$startfrom++;
						}
						$tabledump .= "INSERT INTO $table VALUES ($t);\n";
					} else {
						$complete = FALSE;
						break 2;
					}
				}
			}
		} else {
//dump($currsize);
//dump($_GET['sizelimit']);
			while($currsize + strlen($tabledump) + 500 < $_GET['sizelimit'] * 1000 && $numrows == $offset) {
				if($firstfield['Extra'] == 'auto_increment') {
					$selectsql = "SELECT * FROM $table WHERE $firstfield[Field] > $startfrom LIMIT $offset";
				} else {
					$selectsql = "SELECT * FROM $table LIMIT $startfrom, $offset";
				}
//dump($selectsql);
				$tabledumped = 1;
				$rows = DB::query($selectsql);
				$numfields = $db->num_fields($rows);

				if($numrows = DB::num_rows($rows)) {
					$t1 = $comma1 = '';
					while($row = $db->fetch_row($rows)) {
						$t2 = $comma2 = '';
						for($i = 0; $i < $numfields; $i++) {
							$t2 .= $comma2.($_GET['usehex'] && !empty($row[$i]) && (strexists($tablefields[$i]['Type'], 'char') || strexists($tablefields[$i]['Type'], 'text'))? '0x'.bin2hex($row[$i]) : '\''.mysql_escape_string($row[$i]).'\'');
							$comma2 = ',';
						}
						if(strlen($t1) + $currsize + strlen($tabledump) + 500 < $_GET['sizelimit'] * 1000) {
							if($firstfield['Extra'] == 'auto_increment') {
								$startfrom = $row[0];
							} else {
								$startfrom++;
							}
							$t1 .= "$comma1 ($t2)";
							$comma1 = ',';
						} else {
							$tabledump .= "INSERT INTO $table VALUES $t1;\n";
							$complete = FALSE;
							break 2;
						}
					}
					$tabledump .= "INSERT INTO $table VALUES $t1;\n";
				}
			}
		}

		$startrow = $startfrom;
		$tabledump .= "\n";
	}

	return $tabledump;
}


function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}
?>