<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$memberid = getgpc('memberid');
$member_info = C::t(GM('cheyouhui_member'))->fetch($memberid);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','member_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','member_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','membername'),lang('plugin/yiqixueba','membertitle'),lang('plugin/yiqixueba','membersort'),lang('plugin/yiqixueba','createtime'),lang('plugin/yiqixueba','status'),''));
		$members_row = C::t(GM('cheyouhui_member'))->range();
		foreach($members_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[memberid]\" />",
				'<span class="bold">'.$row['membername'].'</span>',
				$row['membertitle'],
				$row['membersort'],
				dgmdate($row['createtime'],'d'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['memberid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&memberid=$row[memberid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_new_member').'</a></div></td></tr>';
		echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_base_member').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1, '', 'td25'], [1,'<input name="newdisplayorder[]" value="0" size="3" type="text" class="txt">', 'td25'],[1, '<input name="newnode[]" value="" size="15" type="text">'],[1, '<input name="newtitle[]" value="" size="15" type="text">'],[4,'']],
	];
</script>
EOT;
	}else{
		if($_GET['delete']) {
			C::t(GM('cheyouhui_member'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_member_succeed'), 'action='.$this_page.'&subop=memberlist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_member_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','member_option'));
		$images = '';
		if($member_info['memberimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $member_info['memberimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$member_info['memberimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$memberid ? showhiddenfields(array('memberid'=>$memberid)) : '';
		showsetting(lang('plugin/yiqixueba','memberstatus'),'status',$member_info['status'],'radio','',0,lang('plugin/yiqixueba','memberstatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','membername'),'membername',$member_info['membername'],'text',$memberid ?'readonly':'',0,lang('plugin/yiqixueba','membername_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','membertitle'),'membertitle',$member_info['membertitle'],'textarea','',0,lang('plugin/yiqixueba','membertitle_comment'),'','',true);//textarea

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($member_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

		showsetting(lang('plugin/yiqixueba','membersort'),array('membersort', array(
			array(0, lang('plugin/yiqixueba','membersort').'1'),
			array(1, lang('plugin/yiqixueba','membersort').'2'),
			array(2, lang('plugin/yiqixueba','membersort').'3'),
			array(3, lang('plugin/yiqixueba','membersort').'4')
		)),$member_info['membersort'],'select','',0,lang('plugin/yiqixueba','membersort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


		showsetting(lang('plugin/yiqixueba','memberimages'),'memberimages',$member_info['memberimages'],'file','',0,lang('plugin/yiqixueba','memberimages_comment').$imageshtml,'','',true);//file filetext

		showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="memberdescription" style="width:700px;height:200px;visibility:hidden;">'.$member_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="memberdescription"]', {
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
		$membername = dhtmlspecialchars(trim($_GET['membername']));
		$membertitle = strip_tags(trim($_GET['membertitle']));
		$status	= intval($_GET['status']);
		$createtime	= strtotime($_GET['createtime']);
		$description = dhtmlspecialchars(trim($_GET['description']));
		$membersort = trim($_GET['membersort']);

		if(!$membername){
			cpmsg(lang('plugin/yiqixueba','membername_invalid'), '', 'error');
		}
		if(!ispluginkey($membername)) {
			cpmsg(lang('plugin/yiqixueba','membername_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['memberimages']);
		if($_FILES['memberimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['memberimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['memberimages'])) {
			$valueparse = parse_url(addslashes($_POST['memberimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['memberimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['memberimages']));
			}
			$ico = '';
		}
		$data = array(
			'membername' => $membername,
			'membertitle' => $membertitle,
			'description' => $description,
			'memberimages' => $ico,
			'membersort' => $membersort,
			'status' => $status,
			'createtime' => $createtime,
		);
		if($memberid){
			$data['updatetime'] = time();
			C::t(GM('cheyouhui_member'))->update($memberid,$data);
		}else{
			C::t(GM('cheyouhui_member'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_member_succeed'), 'action='.$this_page.'&subop=memberlist', 'succeed');
	}


}

?>