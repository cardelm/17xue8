<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$subops = array('list','export','shengcheng','field','fieldedit');
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
				$row['status'] ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=export&infotypeid=$row[infotypeid]\" >".lang('plugin/yiqixueba','export')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=field&infotypeid=$row[infotypeid]\" >".lang('plugin/yiqixueba','field')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shengcheng&infotypeid=$row[infotypeid]\" >".lang('plugin/yiqixueba','shengcheng')."</a>" : "",
			));
		}
		echo '<tr><td></td><td colspan="5"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_infotype').'</a></div></td></tr>';
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
}elseif($subop == 'field') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','field_list_tips'));
		showformheader($this_page.'&subop=field&infotypeid='.$infotypeid);
		showtableheader($cyhtable_info['infotypetitle'].lang('plugin/yiqixueba','fieldlist'));
		showsubtitle(array('', 'display_order', 'name', 'threadtype_variable', 'threadtype_type', 'available', 'required', 'unchangeable', lang('plugin/yiqixueba','listdisplay'),'threadtype_infotypes_formsearch',  ''));
		$fields = C::t(GM('cheyouhui_field'))->fetch_all_by_infotype($cyhtable_info['infotypename']);
		foreach($fields as $k=>$v ){
			showtablerow('', array('class="td25"','', '','','','','','','',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$v[fieldid]\" />",
				"<input type=\"text\" size=\"4\" name=\"displayordernew[$v[fieldid]]\" value=\"$v[displayorder]\">",
				"<input type=\"text\" class=\"txt\" name=\"titlenew[$v[fieldid]]\" value=\"$v[title]\">",
				$v['name'],
				$lang['threadtype_edit_vars_type_'.$v['type']],
				"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$v[fieldid]]\" value=\"1\" ".($v['available'] ? " checked" : "" )." />",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"requirednew[$v[fieldid]]\" value=\"1\" ".($v['required'] ? " checked" : "" )." />",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"unchangeablenew[$v[fieldid]]\" value=\"1\" ".($v['unchangeable'] ? " checked" : "" )." />",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"listdisplay[$v[fieldid]]\" value=\"1\" ".($v['listdisplay'] ? " checked" : "" )." />",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"searchnew[$v[fieldid]]\" value=\"1\" ".($v['search'] ? " checked" : "" )." />",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=fieldedit&fieldid=".$v['fieldid']."&infotypeid=".$infotypeid."\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="10"><div><a href="###" onclick="addrow(this, 0,0)" class="addtr">'.$lang['threadtype_infotypes_add_option'].'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[
			[1, '', 'td25'],
			[1, '<input type="text" class="txt" size="2" name="newdisplayorder[]" value="0">', 'td25'],
			[1, '<input type="text" class="txt" size="15" name="newtitle[]">'],
			[1, '<input type="text" class="txt" size="15" name="newname[]">'],
			[1, '<select name="newtype[]"><option value="number">$lang[threadtype_edit_vars_type_number]</option><option value="text" selected>$lang[threadtype_edit_vars_type_text]</option><option value="textarea">$lang[threadtype_edit_vars_type_textarea]</option><option value="radio">$lang[threadtype_edit_vars_type_radio]</option><option value="checkbox">$lang[threadtype_edit_vars_type_checkbox]</option><option value="select">$lang[threadtype_edit_vars_type_select]</option><option value="calendar">$lang[threadtype_edit_vars_type_calendar]</option><option value="email">$lang[threadtype_edit_vars_type_email]</option><option value="image">$lang[threadtype_edit_vars_type_image]</option><option value="url">$lang[threadtype_edit_vars_type_url]</option><option value="range">$lang[threadtype_edit_vars_type_range]</option></select>'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newavailable[]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newrequired[]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newunchangeable[]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newsearch[]" value="1">'],
			[1, ''],
		],
	];
</script>
EOT;
	}else{
		if(is_array($_GET['titlenew'])){
			foreach ($_GET['titlenew'] as $k => $v ){
				$v = dhtmlspecialchars(trim($v));
				$displayordernew = intval($_GET['displayordernew'][$k]);
				$availablenew = intval($_GET['availablenew'][$k]);
				$requirednew = intval($_GET['requirednew'][$k]);
				$unchangeablenew = intval($_GET['unchangeablenew'][$k]);
				$listdisplay = intval($_GET['listdisplay'][$k]);
				$searchnew = intval($_GET['searchnew'][$k]);
				if($v){
					$data = array(
						'title' => $v,
						'displayorder' => $displayordernew,
						'available' => $availablenew,
						'required' => $requirednew,
						'unchangeable' => $unchangeablenew,
						'listdisplay' => $listdisplay,
						'search' => $searchnew,
					);
					C::t(GM('cheyouhui_field'))->update($k,$data);
				}
			}
		}
		if(is_array($_GET['newtitle'])){
			foreach ( $_GET['newtitle'] as $k => $v ){
				$v = dhtmlspecialchars(trim($v));
				$newdisplayorder = intval($_GET['newdisplayorder'][$k]);
				$newname = dhtmlspecialchars(trim($_GET['newname'][$k]));
				$newtype = dhtmlspecialchars(trim($_GET['newtype'][$k]));
				$newavailable = intval($_GET['newavailable'][$k]);
				$newrequired = intval($_GET['newrequired'][$k]);
				$newunchangeable = intval($_GET['newunchangeable'][$k]);
				$newsearch = intval($_GET['newsearch'][$k]);
				if($v && $newname){
					$data = array(
						'name' => $newname,
						'title' => $v,
						'displayorder' => $newdisplayorder,
						'type' => $newtype,
						'available' => $newavailable,
						'required' => $newrequired,
						'unchangeable' => $newunchangeable,
						'search' => $newsearch,
						'infotype' => $cyhtable_info['infotypename'],
					);
					C::t(GM('cheyouhui_field'))->insert($data);
				}
			}
		}
		if($_GET['delete']) {
			C::t(GM('cheyouhui_field'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_field_succeed'), 'action='.$this_page.'&subop=field&infotypeid='.$infotypeid, 'succeed');
	}


}elseif($subop == 'fieldedit') {
	$fieldid = getgpc('fieldid');
	$field_info = C::t(GM('cheyouhui_field'))->fetch($fieldid);
	if(!submitcheck('submit')) {
		$typeselect = '<select name="typenew" onchange="var styles, key;styles=new Array(\'number\',\'text\',\'radio\', \'checkbox\', \'textarea\', \'select\', \'image\', \'calendar\', \'range\', \'info\'); for(key in styles) {var obj=$(\'style_\'+styles[key]); if(obj) { obj.style.display=styles[key]==this.options[this.selectedIndex].value?\'\':\'none\';}}">';
		foreach(array('number', 'text', 'radio', 'checkbox', 'textarea', 'select', 'calendar', 'email', 'url', 'image', 'range') as $type) {
			$typeselect .= '<option value="'.$type.'" '.($field_info['type'] == $type ? 'selected' : '').'>'.$lang['threadtype_edit_vars_type_'.$type].'</option>';
		}
		$typeselect .= '</select>';
		$field_info['rules'] = dunserialize($field_info['rules']);
		$field_info['protect'] = dunserialize($field_info['protect']);

		$groups = $forums = array();
		foreach(C::t('common_usergroup')->range() as $group) {
			$groups[] = array($group['groupid'], $group['grouptitle']);
		}
		$verifys = array();
		if($_G['setting']['verify']['enabled']) {
			foreach($_G['setting']['verify'] as $key => $verify) {
				if($verify['available'] == 1) {
					$verifys[] = array($key, $verify['title']);
				}
			}
		}

		foreach(C::t('common_member_profile_setting')->fetch_all_by_available_formtype(1, 'text') as $result) {
			$threadtype_profile = !$threadtype_profile ? "<select id='rules[text][profile]' name='rules[text][profile]'><option value=''></option>" : $threadtype_profile."<option value='{$result[fieldid]}' ".($field_info['rules']['profile'] == $result['fieldid'] ? "selected='selected'" : '').">{$result[title]}</option>";
		}
		$threadtype_profile .= "</select>";
		showtips(lang('plugin/yiqixueba','edit_field_tips'));
		showformheader($this_page.'&subop=fieldedit&fieldid='.$fieldid,'enctype');
		showtableheader(lang('plugin/yiqixueba','field_info'));
		$field_infotype_title = '';
		foreach ( $infotypes as $k => $v ){
			if($field_info['infotype'] == $v['infotypename']){
				$field_infotype_title = $v['infotypetitle'];
			}
		}
		showtablerow('', array( ''), array(
			lang('plugin/yiqixueba','infotype').'&nbsp;&nbsp;<span class="bold">'.$field_infotype_title.'</span>',
		));
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','field_option'));
		$fieldid ? showhiddenfields(array('fieldid'=>$fieldid,'infotypeid'=>$infotypeid)) : '';
		showsetting('name', 'titlenew', $field_info['title'], 'text');
		showsetting('threadtype_variable', 'namenew', $field_info['name'], 'text');
		showsetting('type', '', '', $typeselect);
		showsetting('threadtype_edit_desc', 'descriptionnew', $field_info['description'], 'textarea');
		showsetting('threadtype_unit', 'unitnew', $field_info['unit'], 'text');
		showsetting('threadtype_expiration', 'expirationnew', $field_info['expiration'], 'radio');
		if(in_array($field_info['type'], array('calendar', 'number', 'text', 'email', 'textarea'))) {
			showsetting('threadtype_protect', 'protectnew[status]', $field_info['protect']['status'], 'radio', 0, 1);
			showsetting('threadtype_protect_mode', array('protectnew[mode]', array(
				array(1, $lang['threadtype_protect_mode_pic']),
				array(2, $lang['threadtype_protect_mode_html'])
			)), $field_info['protect']['mode'], 'mradio');
			showsetting('threadtype_protect_usergroup', array('protectnew[usergroup][]', $groups), explode("\t", $field_info['protect']['usergroup']), 'mselect');
			$verifys && showsetting('threadtype_protect_verify', array('protectnew[verify][]', $verifys), explode("\t", $field_info['protect']['verify']), 'mselect');
			showsetting('threadtype_protect_permprompt', 'permpromptnew', $field_info['permprompt'], 'textarea');
		}

		showtagheader('tbody', "style_calendar", $field_info['type'] == 'calendar');
		showtitle('threadtype_edit_vars_type_calendar');
		showsetting('threadtype_edit_inputsize', 'rules[calendar][inputsize]', $field_info['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_number", $field_info['type'] == 'number');
		showtitle('threadtype_edit_vars_type_number');
		showsetting('threadtype_edit_maxnum', 'rules[number][maxnum]', $field_info['rules']['maxnum'], 'text');
		showsetting('threadtype_edit_minnum', 'rules[number][minnum]', $field_info['rules']['minnum'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[number][inputsize]', $field_info['rules']['inputsize'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[number][defaultvalue]', $field_info['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_text", $field_info['type'] == 'text');
		showtitle('threadtype_edit_vars_type_text');
		showsetting('threadtype_edit_textmax', 'rules[text][maxlength]', $field_info['rules']['maxlength'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[text][inputsize]', $field_info['rules']['inputsize'], 'text');
		showsetting('threadtype_edit_profile', '', '', $threadtype_profile);
		showsetting('threadtype_defaultvalue', 'rules[text][defaultvalue]', $field_info['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_textarea", $field_info['type'] == 'textarea');
		showtitle('threadtype_edit_vars_type_textarea');
		showsetting('threadtype_edit_textmax', 'rules[textarea][maxlength]', $field_info['rules']['maxlength'], 'text');
		showsetting('threadtype_edit_colsize', 'rules[textarea][colsize]', $field_info['rules']['colsize'], 'text');
		showsetting('threadtype_edit_rowsize', 'rules[textarea][rowsize]', $field_info['rules']['rowsize'], 'text');
		showsetting('threadtype_defaultvalue', 'rules[textarea][defaultvalue]', $field_info['rules']['defaultvalue'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_select", $field_info['type'] == 'select');
		showtitle('threadtype_edit_vars_type_select');
		showsetting('threadtype_edit_select_choices', 'rules[select][choices]', $field_info['rules']['choices'], 'textarea');
		showsetting('threadtype_edit_inputsize', 'rules[select][inputsize]', $field_info['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_radio", $field_info['type'] == 'radio');
		showtitle('threadtype_edit_vars_type_radio');
		showsetting('threadtype_edit_choices', 'rules[radio][choices]', $field_info['rules']['choices'], 'textarea');
		showtagfooter('tbody');

		showtagheader('tbody', "style_checkbox", $field_info['type'] == 'checkbox');
		showtitle('threadtype_edit_vars_type_checkbox');
		showsetting('threadtype_edit_choices', 'rules[checkbox][choices]', $field_info['rules']['choices'], 'textarea');
		showtagfooter('tbody');

		showtagheader('tbody', "style_image", $field_info['type'] == 'image');
		showtitle('threadtype_edit_vars_type_image');
		showsetting('threadtype_edit_images_weight', 'rules[image][maxwidth]', $field_info['rules']['maxwidth'], 'text');
		showsetting('threadtype_edit_images_height', 'rules[image][maxheight]', $field_info['rules']['maxheight'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[image][inputsize]', $field_info['rules']['inputsize'], 'text');
		showtagfooter('tbody');

		showtagheader('tbody', "style_range", $field_info['type'] == 'range');
		showtitle('threadtype_edit_vars_type_range');
		showsetting('threadtype_edit_maxnum', 'rules[range][maxnum]', $field_info['rules']['maxnum'], 'text');
		showsetting('threadtype_edit_minnum', 'rules[range][minnum]', $field_info['rules']['minnum'], 'text');
		showsetting('threadtype_edit_inputsize', 'rules[range][inputsize]', $field_info['rules']['inputsize'], 'text');
		showsetting('threadtype_edit_searchtxt', 'rules[range][searchtxt]', $field_info['rules']['searchtxt'], 'text');
		showtagfooter('tbody');

		showsubmit('submit');
		showtablefooter();
		showformfooter();
	} else {
		$titlenew = trim($_GET['titlenew']);
		$_GET['namenew'] = trim($_GET['namenew']);
		if(!$titlenew || !$_GET['namenew']) {
			cpmsg('threadtype_infotypes_option_invalid', '', 'error');
		}

		if(in_array(strtoupper($_GET['namenew']), $mysql_keywords)) {
			cpmsg('threadtype_infotypes_optionvariable_iskeyword', '', 'error');
		}

//		if(C::t('forum_typeoption')->fetch_all_by_identifier($_GET['identifiernew'], 0, 1, $_GET['optionid']) || strlen($_GET['identifiernew']) > 40  || !ispluginkey($_GET['identifiernew'])) {
//			cpmsg('threadtype_infotypes_optionvariable_invalid', '', 'error');
//		}

		$_GET['protectnew']['usergroup'] = $_GET['protectnew']['usergroup'] ? implode("\t", $_GET['protectnew']['usergroup']) : '';
		$_GET['protectnew']['verify'] = $_GET['protectnew']['verify'] ? implode("\t", $_GET['protectnew']['verify']) : '';

		$data = array(
			'title' => $titlenew,
			'description' => $_GET['descriptionnew'],
			'name' => $_GET['namenew'],
			'type' => $_GET['typenew'],
			'unit' => $_GET['unitnew'],
			'expiration' => $_GET['expirationnew'],
			'protect' => serialize($_GET['protectnew']),
			'rules' => serialize($_GET['rules'][$_GET['typenew']]),
			'permprompt' => $_GET['permpromptnew'],
		);

		C::t(GM('cheyouhui_field'))->update($fieldid,$data);
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_field_succeed'), 'action='.$this_page.'&subop=field&infotypeid='.$infotypeid, 'succeed');
	}
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