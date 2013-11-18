<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];
$infotype = getgpc('infotype');
$infotypes = C::t(GM('cheyouhui_infotype'))->range();
foreach ($infotypes  as $k => $v ){
	if($v['status']){
		$infotypesa[] = $v['infotypename'];
		$infotypestitle[] = $v['infotypetitle'];
		//$infotype_select[] = array($v['infotypename'],$v['infotypetitle'],getselectdivarray($v['infotypename']));
	}
}
$infotype = $infotype ? $infotype : $infotypesa[0];
$infoid = getgpc($infotype.'id');

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','chezheng_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','select_infotype'));
		showsetting(lang('plugin/yiqixueba','infotype'), array('infotype', $infotype_select), $infotype, 'mradio2','',0,'','','',true);
		showtablefooter();
		foreach ($infotypes as $k => $v ){
			if($v['status']){
				showtagheader('div', 'div_'.$v['infotypename'], $v['infotypename'] == $infotype );
				showtableheader($v['infotypetitle'].lang('plugin/yiqixueba','infolist'));
				$fields_info = C::t(GM('cheyouhui_field'))->fetch_all_by_infotype($v['infotypename']);
				$subtitlearray = array();
				$subtitlearray[] = '';
				$subtitlearray[] = 'display_order';
				foreach ($fields_info as $k1 => $v1 ){
					$subtitlearray[] = $v1['title'];
				}
				showsubtitle($subtitlearray);
//				foreach ($fields_info as $k1 => $v1 ){
//					showtablerow('', array('class="td25"','', ), array(
//						"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$v1[fieldid]\" />",
//						"<input type=\"text\" size=\"4\" name=\"displayordernew[$v1[fieldid]][]\" value=\"$v1[displayorder]\">",
//						"<input type=\"text\" class=\"txt\" name=\"titlenew[$v1[fieldid]][]\" value=\"$v1[title]\">",
//						$v1['name'].'ghjgjh',
//						$lang['threadtype_edit_vars_type_'.$v1['type']],
//						"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$v1[fieldid]][]\" value=\"1\" ".($v1['available'] ? " checked" : "" )." />",
//						"<input class=\"checkbox\" type=\"checkbox\" name=\"requirednew[$v1[fieldid]][]\" value=\"1\" ".($v1['required'] ? " checked" : "" )." />",
//						"<input class=\"checkbox\" type=\"checkbox\" name=\"unchangeablenew[$v1[fieldid]][]\" value=\"1\" ".($v1['unchangeable'] ? " checked" : "" )." />",
//						"<input class=\"checkbox\" type=\"checkbox\" name=\"searchnew[$v1[fieldid]][]\" value=\"1\" ".($v1['search'] ? " checked" : "" )." />",
//						"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&fieldid=$v1[fieldid]\" >".lang('plugin/yiqixueba','edit')."</a>",
//					));
//				}
				echo '<tr><td></td><td colspan="'.(count($subtitlearray)).'"><div><a href="###" onclick="addrow(this, 0,\''.$v['infotypename'].'\')" class="addtr">'.$lang['threadtype_infotypes_add_option'].'</a></div></td></tr>';
				showtablefooter();
				showtagfooter('div');
			}
		}
		showtableheader();
		showsubmit('submit', 'submit', 'del');

		showtablefooter();
		showformfooter();
	}else{
		if($_GET['delete']) {
			C::t(GM('cheyouhui_chezheng'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_chezheng_succeed'), 'action='.$this_page.'&subop=chezhenglist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_chezheng_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','chezheng_option'));
		$images = '';
		if($chezheng_info['chezhengimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $chezheng_info['chezhengimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$chezheng_info['chezhengimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$chezhengid ? showhiddenfields(array('chezhengid'=>$chezhengid)) : '';
		showsetting(lang('plugin/yiqixueba','chezhengstatus'),'status',$chezheng_info['status'],'radio','',0,lang('plugin/yiqixueba','chezhengstatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','chezhengname'),'chezhengname',$chezheng_info['chezhengname'],'text',$chezhengid ?'readonly':'',0,lang('plugin/yiqixueba','chezhengname_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','chezhengtitle'),'chezhengtitle',$chezheng_info['chezhengtitle'],'textarea','',0,lang('plugin/yiqixueba','chezhengtitle_comment'),'','',true);//textarea

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($chezheng_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

		showsetting(lang('plugin/yiqixueba','chezhengsort'),array('chezhengsort', array(
			array(0, lang('plugin/yiqixueba','chezhengsort').'1'),
			array(1, lang('plugin/yiqixueba','chezhengsort').'2'),
			array(2, lang('plugin/yiqixueba','chezhengsort').'3'),
			array(3, lang('plugin/yiqixueba','chezhengsort').'4')
		)),$chezheng_info['chezhengsort'],'select','',0,lang('plugin/yiqixueba','chezhengsort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


		showsetting(lang('plugin/yiqixueba','chezhengimages'),'chezhengimages',$chezheng_info['chezhengimages'],'file','',0,lang('plugin/yiqixueba','chezhengimages_comment').$imageshtml,'','',true);//file filetext

		showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="chezhengdescription" style="width:700px;height:200px;visibility:hidden;">'.$chezheng_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="chezhengdescription"]', {
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
		$chezhengname = dhtmlspecialchars(trim($_GET['chezhengname']));
		$chezhengtitle = strip_tags(trim($_GET['chezhengtitle']));
		$status	= intval($_GET['status']);
		$createtime	= strtotime($_GET['createtime']);
		$description = dhtmlspecialchars(trim($_GET['description']));
		$chezhengsort = trim($_GET['chezhengsort']);

		if(!$chezhengname){
			cpmsg(lang('plugin/yiqixueba','chezhengname_invalid'), '', 'error');
		}
		if(!ispluginkey($chezhengname)) {
			cpmsg(lang('plugin/yiqixueba','chezhengname_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['chezhengimages']);
		if($_FILES['chezhengimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['chezhengimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['chezhengimages'])) {
			$valueparse = parse_url(addslashes($_POST['chezhengimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['chezhengimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['chezhengimages']));
			}
			$ico = '';
		}
		$data = array(
			'chezhengname' => $chezhengname,
			'chezhengtitle' => $chezhengtitle,
			'description' => $description,
			'chezhengimages' => $ico,
			'chezhengsort' => $chezhengsort,
			'status' => $status,
			'createtime' => $createtime,
		);
		if($chezhengid){
			$data['updatetime'] = time();
			C::t(GM('cheyouhui_'.$infotype))->update($chezhengid,$data);
		}else{
			C::t(GM('cheyouhui_'.$infotype))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_chezheng_succeed'), 'action='.$this_page.'&subop=chezhenglist', 'succeed');
	}


}

?>