<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','edit_setting_tips'));
	showformheader($this_page,'enctype');
	showtableheader(lang('plugin/yiqixueba','setting_option'));
	$images = '';
	if($setting_info['settingimages']!='') {
		$images = str_replace('{STATICURL}', STATICURL, $setting_info['settingimages']);
		if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
			$images = $_G['setting']['attachurl'].'common/'.$setting_info['settingimages'].'?'.random(6);
		}
		$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
	}
	$settingid ? showhiddenfields(array('settingid'=>$settingid)) : '';
	showsetting(lang('plugin/yiqixueba','settingstatus'),'status',$setting_info['status'],'radio','',0,lang('plugin/yiqixueba','settingstatus_comment'),'','',true);//radio

	showsetting(lang('plugin/yiqixueba','settingname'),'settingname',$setting_info['settingname'],'text',$settingid ?'readonly':'',0,lang('plugin/yiqixueba','settingname_comment'),'','',true);//text password number color

	showsetting(lang('plugin/yiqixueba','settingtitle'),'settingtitle',$setting_info['settingtitle'],'textarea','',0,lang('plugin/yiqixueba','settingtitle_comment'),'','',true);//textarea

	echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
	showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($setting_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

	showsetting(lang('plugin/yiqixueba','settingsort'),array('settingsort', array(
		array(0, lang('plugin/yiqixueba','settingsort').'1'),
		array(1, lang('plugin/yiqixueba','settingsort').'2'),
		array(2, lang('plugin/yiqixueba','settingsort').'3'),
		array(3, lang('plugin/yiqixueba','settingsort').'4')
	)),$setting_info['settingsort'],'select','',0,lang('plugin/yiqixueba','settingsort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


	showsetting(lang('plugin/yiqixueba','settingimages'),'settingimages',$setting_info['settingimages'],'file','',0,lang('plugin/yiqixueba','settingimages_comment').$imageshtml,'','',true);//file filetext

	showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

	showtablefooter();
	showtableheader(lang('plugin/yiqixueba','description').':');
	echo '<tr class="noborder" ><td colspan="2" >';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
	echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
	echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
	echo '<textarea name="settingdescription" style="width:700px;height:200px;visibility:hidden;">'.$setting_info['description'].'</textarea>';
	echo '</td></tr>';
	showsubmit('submit');
	showtablefooter();
	showformfooter();
	echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="settingdescription"]', {
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
	$settingname = dhtmlspecialchars(trim($_GET['settingname']));
	$settingtitle = strip_tags(trim($_GET['settingtitle']));
	$status	= intval($_GET['status']);
	$createtime	= strtotime($_GET['createtime']);
	$description = dhtmlspecialchars(trim($_GET['description']));
	$settingsort = trim($_GET['settingsort']);

	if(!$settingname){
		cpmsg(lang('plugin/yiqixueba','settingname_invalid'), '', 'error');
	}
	if(!ispluginkey($settingname)) {
		cpmsg(lang('plugin/yiqixueba','settingname_invalid'), '', 'error');
	}
	$ico = addslashes($_GET['settingimages']);
	if($_FILES['settingimages']) {
		$upload = new discuz_upload();
		if($upload->init($_FILES['settingimages'], 'common') && $upload->save()) {
			$ico = $upload->attach['attachment'];
		}
	}
	if($_POST['delete'] && addslashes($_POST['settingimages'])) {
		$valueparse = parse_url(addslashes($_POST['settingimages']));
		if(!isset($valueparse['host']) && !strexists(addslashes($_POST['settingimages']), '{STATICURL}')) {
			@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['settingimages']));
		}
		$ico = '';
	}
	$data = array(
		'settingname' => $settingname,
		'settingtitle' => $settingtitle,
		'description' => $description,
		'settingimages' => $ico,
		'settingsort' => $settingsort,
		'status' => $status,
		'createtime' => $createtime,
	);
	if($settingid){
		$data['updatetime'] = time();
		C::t(GM('main_setting'))->update($settingid,$data);
	}else{
		C::t(GM('main_setting'))->insert($data);
	}
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','edit_setting_succeed'), 'action='.$this_page.'&subop=settinglist', 'succeed');
}



?>