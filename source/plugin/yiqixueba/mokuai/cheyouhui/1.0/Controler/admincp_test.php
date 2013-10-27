<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$testid = getgpc('testid');
$test_info = C::t(GM('cheyouhui_test'))->fetch($testid);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','test_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','test_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','testname'),lang('plugin/yiqixueba','testtitle'),lang('plugin/yiqixueba','testsort'),lang('plugin/yiqixueba','createtime'),lang('plugin/yiqixueba','status'),''));
		$tests_row = C::t(GM('cheyouhui_test'))->range();
		foreach($tests_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[testid]\" />",
				'<span class="bold">'.$row['testname'].'</span>',
				$row['testtitle'],
				$row['testsort'],
				dgmdate($row['createtime'],'d'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['testid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&testid=$row[testid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_new_test').'</a></div></td></tr>';
		echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_base_test').'</a></div></td></tr>';
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
			C::t(GM('cheyouhui_test'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_test_succeed'), 'action='.$this_page.'&subop=testlist', 'succeed');
	}	
	
}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_test_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','test_option'));
		$images = '';
		if($test_info['testimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $test_info['testimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$test_info['testimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$testid ? showhiddenfields(array('testid'=>$testid)) : '';
		showsetting(lang('plugin/yiqixueba','teststatus'),'status',$test_info['status'],'radio','',0,lang('plugin/yiqixueba','teststatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','testname'),'testname',$test_info['testname'],'text',$testid ?'readonly':'',0,lang('plugin/yiqixueba','testname_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','testtitle'),'testtitle',$test_info['testtitle'],'textarea','',0,lang('plugin/yiqixueba','testtitle_comment'),'','',true);//textarea 

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($test_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar 

		showsetting(lang('plugin/yiqixueba','testsort'),array('testsort', array(
			array(0, lang('plugin/yiqixueba','testsort').'1'),
			array(1, lang('plugin/yiqixueba','testsort').'2'),
			array(2, lang('plugin/yiqixueba','testsort').'3'),
			array(3, lang('plugin/yiqixueba','testsort').'4')
		)),$test_info['testsort'],'select','',0,lang('plugin/yiqixueba','testsort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


		showsetting(lang('plugin/yiqixueba','testimages'),'testimages',$test_info['testimages'],'file','',0,lang('plugin/yiqixueba','testimages_comment').$imageshtml,'','',true);//file filetext

		showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="testdescription" style="width:700px;height:200px;visibility:hidden;">'.$test_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="testdescription"]', {
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
		$testname = dhtmlspecialchars(trim($_GET['testname']));
		$testtitle = strip_tags(trim($_GET['testtitle']));
		$status	= intval($_GET['status']);
		$createtime	= strtotime($_GET['createtime']);
		$description = dhtmlspecialchars(trim($_GET['description']));
		$testsort = trim($_GET['testsort']);

		if(!$testname){
			cpmsg(lang('plugin/yiqixueba','testname_invalid'), '', 'error');
		}
		if(!ispluginkey($testname)) {
			cpmsg(lang('plugin/yiqixueba','testname_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['testimages']);
		if($_FILES['testimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['testimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['testimages'])) {
			$valueparse = parse_url(addslashes($_POST['testimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['testimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['testimages']));
			}
			$ico = '';
		}
		$data = array(
			'testname' => $testname,
			'testtitle' => $testtitle,
			'description' => $description,
			'testimages' => $ico,
			'testsort' => $testsort,
			'status' => $status,
			'createtime' => $createtime,
		);
		if($testid){
			$data['updatetime'] = time();
			C::t(GM('cheyouhui_test'))->update($testid,$data);
		}else{
			C::t(GM('cheyouhui_test'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_test_succeed'), 'action='.$this_page.'&subop=testlist', 'succeed');
	}


}
	
?>