<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$fieldid = getgpc('fieldid');
$field_info = C::t(GM('cheyouhui_field'))->fetch($fieldid);

$infotype = getgpc('infotype');
$infotypes = C::t(GM('cheyouhui_infotype'))->range();
$tempa = array_keys($infotypes);
$infotype = $infotype ? $infotype : $infotypes[$tempa[0]]['infotypename'];

$fields = C::t(GM('cheyouhui_field'))->range();
foreach ($fields  as $k => $v ){
	$fields_info[$v['infotype']][] = $v;
}
$mysql_keywords = array( 'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 'BEFORE', 'BETWEEN', 'BIGINT', 'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE', 'CASE', 'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN', 'CONDITION', 'CONNECTION', 'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE', 'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND', 'DAY_MINUTE', 'DAY_SECOND', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT', 'DELAYED', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE', 'DROP', 'DUAL', 'EACH', 'ELSE', 'ELSEIF', 'ENCLOSED', 'ESCAPED', 'EXISTS', 'EXIT', 'EXPLAIN', 'FALSE', 'FETCH', 'FLOAT', 'FLOAT4', 'FLOAT8', 'FOR', 'FORCE', 'FOREIGN', 'FROM', 'FULLTEXT', 'GOTO', 'GRANT', 'GROUP', 'HAVING', 'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND', 'IF', 'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 'INOUT', 'INSENSITIVE', 'INSERT', 'INT', 'INT1', 'INT2', 'INT3', 'INT4', 'INT8', 'INTEGER', 'INTERVAL', 'INTO', 'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LABEL', 'LEADING', 'LEAVE', 'LEFT', 'LIKE', 'LIMIT', 'LINEAR', 'LINES', 'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK', 'LONG', 'LONGBLOB', 'LONGTEXT', 'LOOP', 'LOW_PRIORITY', 'MATCH', 'MEDIUMBLOB', 'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND', 'MOD', 'MODIFIES', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL', 'NUMERIC', 'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUT', 'OUTER', 'OUTFILE', 'PRECISION', 'PRIMARY', 'PROCEDURE', 'PURGE', 'RAID0', 'RANGE', 'READ', 'READS', 'REAL', 'REFERENCES', 'REGEXP', 'RELEASE', 'RENAME', 'REPEAT', 'REPLACE', 'REQUIRE', 'RESTRICT', 'RETURN', 'REVOKE', 'RIGHT', 'RLIKE', 'SCHEMA', 'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE', 'SEPARATOR', 'SET', 'SHOW', 'SMALLINT', 'SPATIAL', 'SPECIFIC', 'SQL', 'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT', 'SQL_CALC_FOUND_ROWS', 'SQL_SMALL_RESULT', 'SSL', 'STARTING', 'STRAIGHT_JOIN', 'TABLE', 'TERMINATED', 'THEN', 'TINYBLOB', 'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRIGGER', 'TRUE', 'UNDO', 'UNION', 'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE', 'USE', 'USING', 'UTC_DATE', 'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES', 'VARBINARY', 'VARCHAR', 'VARCHARACTER', 'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH', 'WRITE', 'X509', 'XOR', 'YEAR_MONTH', 'ZEROFILL', 'ACTION', 'BIT', 'DATE', 'ENUM', 'NO', 'TEXT', 'TIME');

if($subop == 'list') {
	if(!submitcheck('submit')) {
		foreach ($infotypes  as $k => $v ){
			if($v['status']){
				$infotype_select[] = array($v['infotypename'],$v['infotypetitle'],getselectdivarray($v['infotypename']));
			}
		}
		showtips(lang('plugin/yiqixueba','field_list_tips'));
		showformheader($this_page.'&subop=list&infotype='.$infotype);
		showtableheader(lang('plugin/yiqixueba','select_infotype'));
		showsetting(lang('plugin/yiqixueba','infotype'), array('infotype', $infotype_select), $infotype, 'mradio2','',0,'','','',true);
		showtablefooter();
		
		foreach ($infotypes as $k => $v ){
			if($v['status']){
				showtagheader('div', 'div_'.$v['infotypename'], $v['infotypename'] == $infotype );
				showtableheader($v['infotypetitle'].lang('plugin/yiqixueba','fieldlist'));
				showsubtitle(array('', 'display_order', 'name', 'threadtype_variable', 'threadtype_type', 'available', 'required', 'unchangeable', 'threadtype_infotypes_formsearch',  ''));
				foreach ($fields_info[$v['infotypename']] as $k1 => $v1 ){
					showtablerow('', array('class="td25"','', '','','','','','',''), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$v1[fieldid]\" />",
						"<input type=\"text\" size=\"4\" name=\"displayordernew[$v1[fieldid]][]\" value=\"$v1[displayorder]\">",
						"<input type=\"text\" class=\"txt\" name=\"titlenew[$v1[fieldid]][]\" value=\"$v1[title]\">",
						$v1['name'],
						$lang['threadtype_edit_vars_type_'.$v1['type']],
						"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$v1[fieldid]][]\" value=\"1\" ".($v1['available'] ? " checked" : "" )." />",
						"<input class=\"checkbox\" type=\"checkbox\" name=\"requirednew[$v1[fieldid]][]\" value=\"1\" ".($v1['required'] ? " checked" : "" )." />",
						"<input class=\"checkbox\" type=\"checkbox\" name=\"unchangeablenew[$v1[fieldid]][]\" value=\"1\" ".($v1['unchangeable'] ? " checked" : "" )." />",
						"<input class=\"checkbox\" type=\"checkbox\" name=\"searchnew[$v1[fieldid]][]\" value=\"1\" ".($v1['search'] ? " checked" : "" )." />",
						"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&fieldid=$v1[fieldid]\" >".lang('plugin/yiqixueba','edit')."</a>",
					));
				}
				echo '<tr><td></td><td colspan="9"><div><a href="###" onclick="addrow(this, 0,\''.$v['infotypename'].'\')" class="addtr">'.$lang['threadtype_infotypes_add_option'].'</a></div></td></tr>';
				showtablefooter();
				showtagfooter('div');
			}
		}
		showtableheader();
		showsubmit('submit', 'submit', 'del');

		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[
			[1, '', 'td25'],
			[1, '<input type="text" class="txt" size="2" name="newdisplayorder[{1}][]" value="0">', 'td25'],
			[1, '<input type="text" class="txt" size="15" name="newtitle[{1}][]">'],
			[1, '<input type="text" class="txt" size="15" name="newname[{1}][]">'],
			[1, '<select name="newtype[{1}][]"><option value="number">$lang[threadtype_edit_vars_type_number]</option><option value="text" selected>$lang[threadtype_edit_vars_type_text]</option><option value="textarea">$lang[threadtype_edit_vars_type_textarea]</option><option value="radio">$lang[threadtype_edit_vars_type_radio]</option><option value="checkbox">$lang[threadtype_edit_vars_type_checkbox]</option><option value="select">$lang[threadtype_edit_vars_type_select]</option><option value="calendar">$lang[threadtype_edit_vars_type_calendar]</option><option value="email">$lang[threadtype_edit_vars_type_email]</option><option value="image">$lang[threadtype_edit_vars_type_image]</option><option value="url">$lang[threadtype_edit_vars_type_url]</option><option value="range">$lang[threadtype_edit_vars_type_range]</option></select>'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newavailable[{1}][]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newrequired[{1}][]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newunchangeable[{1}][]" value="1">'],
			[1, '<input type="checkbox" class="checkbox" size="15" name="newsearch[{1}][]" value="1">'],
			[1, ''],
		],
	];
</script>
EOT;
	}else{
		if(is_array($_GET['titlenew'])){
			foreach ($_GET['titlenew'] as $k => $v ){
				if(is_array($v)){
					foreach ( $v as $k1 => $v1 ){
						$v1 = dhtmlspecialchars(trim($v1));
						$displayordernew = intval($_GET['displayordernew'][$k][$k1]);
						$availablenew = intval($_GET['availablenew'][$k][$k1]);
						$requirednew = intval($_GET['requirednew'][$k][$k1]);
						$unchangeablenew = intval($_GET['unchangeablenew'][$k][$k1]);
						$searchnew = intval($_GET['searchnew'][$k][$k1]);
						if($v1){
							$data = array(
								'title' => $v1,	
								'displayorder' => $displayordernew,	
								'available' => $availablenew,	
								'required' => $requirednew,	
								'unchangeable' => $unchangeablenew,	
								'search' => $searchnew,	
							);
							C::t(GM('cheyouhui_field'))->update($k,$data);
						}
					}
				}
			}
		}
		if(is_array($_GET['newtitle'])){
			foreach ($_GET['newtitle'] as $k => $v ){
				if(is_array($v)){
					foreach ( $v as $k1 => $v1 ){
						$v1 = dhtmlspecialchars(trim($v1));
						$newdisplayorder = intval($_GET['newdisplayorder'][$k][$k1]);
						$newname = dhtmlspecialchars(trim($_GET['newname'][$k][$k1]));
						$newtype = dhtmlspecialchars(trim($_GET['newtype'][$k][$k1]));
						$newavailable = intval($_GET['newavailable'][$k][$k1]);
						$newrequired = intval($_GET['newrequired'][$k][$k1]);
						$newunchangeable = intval($_GET['newunchangeable'][$k][$k1]);
						$newsearch = intval($_GET['newsearch'][$k][$k1]);
						if($v1 && $newname){
							$data = array(
								'name' => $newname,	
								'title' => $v1,	
								'displayorder' => $newdisplayorder,	
								'type' => $newtype,	
								'available' => $newavailable,	
								'required' => $newrequired,	
								'unchangeable' => $newunchangeable,	
								'search' => $newsearch,	
								'infotype' => $k,	
							);
							C::t(GM('cheyouhui_field'))->insert($data);
						}
					}
				}
			}
		}
		if($_GET['delete']) {
			C::t(GM('cheyouhui_field'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_field_succeed'), 'action='.$this_page.'&subop=list&infotype='.$infotype, 'succeed');
	}

}elseif($subop == 'edit') {
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
		showformheader($this_page.'&subop=edit','enctype');
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
		$fieldid ? showhiddenfields(array('fieldid'=>$fieldid)) : '';
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
		cpmsg(lang('plugin/yiqixueba','edit_field_succeed'), 'action='.$this_page.'&subop=fieldlist', 'succeed');
	}


}

?>