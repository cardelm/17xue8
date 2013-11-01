<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

if(!submitcheck('submit')) {
	showtips(lang('plugin/yiqixueba','edit_basesetting_tips'));
	showformheader($this_page.'&subop=edit','enctype');
	showtableheader(lang('plugin/yiqixueba','basesetting_option'));
	$images = '';
	if($basesetting_info['basesettingimages']!='') {
		$images = str_replace('{STATICURL}', STATICURL, $basesetting_info['basesettingimages']);
		if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
			$images = $_G['setting']['attachurl'].'common/'.$basesetting_info['basesettingimages'].'?'.random(6);
		}
		$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
	}
	$basesettingid ? showhiddenfields(array('basesettingid'=>$basesettingid)) : '';
	showsetting(lang('plugin/yiqixueba','basesettingstatus'),'status',$basesetting_info['status'],'radio','',0,lang('plugin/yiqixueba','basesettingstatus_comment'),'','',true);//radio

	showsetting(lang('plugin/yiqixueba','basesettingname'),'basesettingname',$basesetting_info['basesettingname'],'text',$basesettingid ?'readonly':'',0,lang('plugin/yiqixueba','basesettingname_comment'),'','',true);//text password number color

	showsetting(lang('plugin/yiqixueba','basesettingtitle'),'basesettingtitle',$basesetting_info['basesettingtitle'],'textarea','',0,lang('plugin/yiqixueba','basesettingtitle_comment'),'','',true);//textarea

	echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
	showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($basesetting_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

	showsetting(lang('plugin/yiqixueba','basesettingsort'),array('basesettingsort', array(
		array(0, lang('plugin/yiqixueba','basesettingsort').'1'),
		array(1, lang('plugin/yiqixueba','basesettingsort').'2'),
		array(2, lang('plugin/yiqixueba','basesettingsort').'3'),
		array(3, lang('plugin/yiqixueba','basesettingsort').'4')
	)),$basesetting_info['basesettingsort'],'select','',0,lang('plugin/yiqixueba','basesettingsort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


	showsetting(lang('plugin/yiqixueba','basesettingimages'),'basesettingimages',$basesetting_info['basesettingimages'],'file','',0,lang('plugin/yiqixueba','basesettingimages_comment').$imageshtml,'','',true);//file filetext

	showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

	showtablefooter();
	showtableheader(lang('plugin/yiqixueba','description').':');
	echo '<tr class="noborder" ><td colspan="2" >';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
	echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
	echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
	echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
	echo '<textarea name="basesettingdescription" style="width:700px;height:200px;visibility:hidden;">'.$basesetting_info['description'].'</textarea>';
	echo '</td></tr>';
	showsubmit('submit');
	showtablefooter();
	showformfooter();
	echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="basesettingdescription"]', {
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
	$basesettingname = dhtmlspecialchars(trim($_GET['basesettingname']));
	$basesettingtitle = strip_tags(trim($_GET['basesettingtitle']));
	$status	= intval($_GET['status']);
	$createtime	= strtotime($_GET['createtime']);
	$description = dhtmlspecialchars(trim($_GET['description']));
	$basesettingsort = trim($_GET['basesettingsort']);

	if(!$basesettingname){
		cpmsg(lang('plugin/yiqixueba','basesettingname_invalid'), '', 'error');
	}
	if(!ispluginkey($basesettingname)) {
		cpmsg(lang('plugin/yiqixueba','basesettingname_invalid'), '', 'error');
	}
	$ico = addslashes($_GET['basesettingimages']);
	if($_FILES['basesettingimages']) {
		$upload = new discuz_upload();
		if($upload->init($_FILES['basesettingimages'], 'common') && $upload->save()) {
			$ico = $upload->attach['attachment'];
		}
	}
	if($_POST['delete'] && addslashes($_POST['basesettingimages'])) {
		$valueparse = parse_url(addslashes($_POST['basesettingimages']));
		if(!isset($valueparse['host']) && !strexists(addslashes($_POST['basesettingimages']), '{STATICURL}')) {
			@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['basesettingimages']));
		}
		$ico = '';
	}
	$data = array(
		'basesettingname' => $basesettingname,
		'basesettingtitle' => $basesettingtitle,
		'description' => $description,
		'basesettingimages' => $ico,
		'basesettingsort' => $basesettingsort,
		'status' => $status,
		'createtime' => $createtime,
	);
	if($basesettingid){
		$data['updatetime'] = time();
		C::t(GM('shop_basesetting'))->update($basesettingid,$data);
	}else{
		C::t(GM('shop_basesetting'))->insert($data);
	}
	echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
	cpmsg(lang('plugin/yiqixueba','edit_basesetting_succeed'), 'action='.$this_page.'&subop=basesettinglist', 'succeed');
}

?>