<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$exampleid = getgpc('exampleid');
$example_info = C::t(GM('server_example'))->fetch($exampleid);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','example_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','example_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','examplename'),lang('plugin/yiqixueba','exampletitle'),lang('plugin/yiqixueba','examplesort'),lang('plugin/yiqixueba','createtime'),lang('plugin/yiqixueba','status'),''));
		$examples_row = C::t(GM('server_example'))->range();
		foreach($examples_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[exampleid]\" />",
				'<span class="bold">'.$row['examplename'].'</span>',
				$row['exampletitle'],
				$row['examplesort'],
				dgmdate($row['createtime'],'d'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['exampleid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&exampleid=$row[exampleid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_new_example').'</a></div></td></tr>';
		echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_base_example').'</a></div></td></tr>';
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
			C::t(GM('server_example'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_example_succeed'), 'action='.$this_page.'&subop=examplelist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_example_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','example_option'));
		$images = '';
		if($example_info['exampleimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $example_info['exampleimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$example_info['exampleimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$exampleid ? showhiddenfields(array('exampleid'=>$exampleid)) : '';
		showsetting(lang('plugin/yiqixueba','examplestatus'),'status',$example_info['status'],'radio','',0,lang('plugin/yiqixueba','examplestatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','examplename'),'examplename',$example_info['examplename'],'text',$exampleid ?'readonly':'',0,lang('plugin/yiqixueba','examplename_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','exampletitle'),'exampletitle',$example_info['exampletitle'],'textarea','',0,lang('plugin/yiqixueba','exampletitle_comment'),'','',true);//textarea

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($example_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

		showsetting(lang('plugin/yiqixueba','examplesort'),array('examplesort', array(
			array(0, lang('plugin/yiqixueba','examplesort').'1'),
			array(1, lang('plugin/yiqixueba','examplesort').'2'),
			array(2, lang('plugin/yiqixueba','examplesort').'3'),
			array(3, lang('plugin/yiqixueba','examplesort').'4')
		)),$example_info['examplesort'],'select','',0,lang('plugin/yiqixueba','examplesort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


		showsetting(lang('plugin/yiqixueba','exampleimages'),'exampleimages',$example_info['exampleimages'],'file','',0,lang('plugin/yiqixueba','exampleimages_comment').$imageshtml,'','',true);//file filetext

		showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="exampledescription" style="width:700px;height:200px;visibility:hidden;">'.$example_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="exampledescription"]', {
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
		$examplename = dhtmlspecialchars(trim($_GET['examplename']));
		$exampletitle = strip_tags(trim($_GET['exampletitle']));
		$status	= intval($_GET['status']);
		$createtime	= strtotime($_GET['createtime']);
		$description = dhtmlspecialchars(trim($_GET['description']));
		$examplesort = trim($_GET['examplesort']);

		if(!$examplename){
			cpmsg(lang('plugin/yiqixueba','examplename_invalid'), '', 'error');
		}
		if(!ispluginkey($examplename)) {
			cpmsg(lang('plugin/yiqixueba','examplename_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['exampleimages']);
		if($_FILES['exampleimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['exampleimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['exampleimages'])) {
			$valueparse = parse_url(addslashes($_POST['exampleimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['exampleimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['exampleimages']));
			}
			$ico = '';
		}
		$data = array(
			'examplename' => $examplename,
			'exampletitle' => $exampletitle,
			'description' => $description,
			'exampleimages' => $ico,
			'examplesort' => $examplesort,
			'status' => $status,
			'createtime' => $createtime,
		);
		if($exampleid){
			$data['updatetime'] = time();
			C::t(GM('server_example'))->update($exampleid,$data);
		}else{
			C::t(GM('server_example'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_example_succeed'), 'action='.$this_page.'&subop=examplelist', 'succeed');
	}


}

?>