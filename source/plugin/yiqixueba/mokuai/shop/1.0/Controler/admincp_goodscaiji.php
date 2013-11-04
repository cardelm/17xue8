<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

//$lashoucitys = getlashoucity();
//getlashoupage($url)
$url = 'http://anqing.lashou.com';
$deallink = getdeallink($url);
		$url = 'http://anqing.lashou.com/deal/'.$deallink[5].'.html';
		$goodslist_text = file_get_contents($url);
		$gp0 = explode('<div id="main">',$goodslist_text);
		$gp1 = explode('<div class="sort" id="offline_sort" style="display:none">',$gp0[1]);
		preg_match_all('|<a href="/cate.*</a>|',$gp1[0],$rsT);
		foreach($rsT as $k1=>$v1 ){
			foreach($v1 as $k2=>$v2 ){
				$gp2 = explode('title="',$v2);
				$gp3 = explode('/',trim(str_replace('"','',str_replace('<a href="/cate/','',$gp2[0]))));
				$gp4 = explode('">',trim($gp2[1]));
				if($gp3[0] =='all' && $gp3[1]){
					$data['diqu']['name'] = str_replace('-all','',$gp3[1]);
					$data['diqu']['title'] = trim($gp4[0]);
				}else{
					$data['fenlei'][] = array('name'=>trim($gp3[0]),'title'=>trim($gp4[0]));
				}
			}
		}
		$gp5 = explode('<span class="right">',$gp1[0]);
		$gp6 = explode('</span',$gp5[1]);
		$data['title'] = $gp6[0];

		$gp7 = explode('<div class="deal-intro">',$gp1[1]);
		$gp8 = explode('<div class="deal-content">',$gp7[1]);
		$data['subtitle'] = trim($gp8[0]);
		$gp9 = explode('<p class="deal-price"><span>&yen;</span>',$gp8[1]);
		$gp10 = explode('</p>',$gp9[1]);
		$data['price'] = $gp10[0];
		$gp11 = explode('<del class="left">&yen;',$gp10[1]);
		$gp12 = explode('</del>',$gp11[1]);
		$data['oldprice'] = $gp12[0];
		$gp13 = explode('<div class="deal-time" time="',$gp8[1]);
		$gp14 = explode('" id="time_over">',$gp13[1]);
		$data['deal-time'] = $gp14[0];
		$gp15 = explode('<ul class="com_adr ">',$gp8[1]);
		$gp16 = explode('</ul>',$gp15[1]);
		$gp17 = explode('=',$gp16[0]);
		foreach($gp17 as $k2=>$v2 ){
			//dump($v2);
			if(substr($v2,0,7) == '"/shop/'){
				$data['shoplink'] = str_replace('.html" class','',str_replace('"/shop/','',$v2));

			}elseif(substr($v2,0,7) == '"link">'){
				$data['shopname'] = substr($v2,7,stripos($v2,'</a>')-7);
			}elseif(substr($v2,0,19) == '"showmapwindow(\'0\','){
				$gp18 = explode(',',$v2);
				$data['shopditu']['x'] = str_replace("'",'',$gp18[1]);
				$data['shopditu']['y'] = str_replace("'",'',$gp18[2]);
			}
		}
		$gp19 = explode('<div class="prodetail" id="deal_lazyload">',$gp8[1]);
		$gp20 = explode('<div id="detail-tag06" class="detail-buy">',$gp19[1]);
		$data['comment'] = trim($gp20[0]);

dump($data);
//dump($gp20[0]);
//dump($gp8[1]);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','goodscaiji_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','goodscaiji_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','goodscaijiname'),lang('plugin/yiqixueba','goodscaijititle'),lang('plugin/yiqixueba','goodscaijisort'),lang('plugin/yiqixueba','createtime'),lang('plugin/yiqixueba','status'),''));

		foreach($goodscaijis_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[goodscaijiid]\" />",
				'<span class="bold">'.$row['goodscaijiname'].'</span>',
				$row['goodscaijititle'],
				$row['goodscaijisort'],
				dgmdate($row['createtime'],'d'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['goodscaijiid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&goodscaijiid=$row[goodscaijiid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="4"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_new_goodscaiji').'</a></div></td></tr>';
		echo '<tr><td></td><td colspan="4"><div><a href="###" onclick="addrow(this, 0, 0)" class="addtr">'.lang('plugin/yiqixueba','add_base_goodscaiji').'</a></div></td></tr>';
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
			C::t(GM('shop_goodscaiji'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_goodscaiji_succeed'), 'action='.$this_page.'&subop=goodscaijilist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_goodscaiji_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','goodscaiji_option'));
		$images = '';
		if($goodscaiji_info['goodscaijiimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $goodscaiji_info['goodscaijiimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$goodscaiji_info['goodscaijiimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$goodscaijiid ? showhiddenfields(array('goodscaijiid'=>$goodscaijiid)) : '';
		showsetting(lang('plugin/yiqixueba','goodscaijistatus'),'status',$goodscaiji_info['status'],'radio','',0,lang('plugin/yiqixueba','goodscaijistatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','goodscaijiname'),'goodscaijiname',$goodscaiji_info['goodscaijiname'],'text',$goodscaijiid ?'readonly':'',0,lang('plugin/yiqixueba','goodscaijiname_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','goodscaijititle'),'goodscaijititle',$goodscaiji_info['goodscaijititle'],'textarea','',0,lang('plugin/yiqixueba','goodscaijititle_comment'),'','',true);//textarea

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','createtime'),'createtime',dgmdate($goodscaiji_info['createtime'],'d'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

		showsetting(lang('plugin/yiqixueba','goodscaijisort'),array('goodscaijisort', array(
			array(0, lang('plugin/yiqixueba','goodscaijisort').'1'),
			array(1, lang('plugin/yiqixueba','goodscaijisort').'2'),
			array(2, lang('plugin/yiqixueba','goodscaijisort').'3'),
			array(3, lang('plugin/yiqixueba','goodscaijisort').'4')
		)),$goodscaiji_info['goodscaijisort'],'select','',0,lang('plugin/yiqixueba','goodscaijisort_comment'),'','',true);//select  mradio mcheckbox mselect (binmcheckbox)


		showsetting(lang('plugin/yiqixueba','goodscaijiimages'),'goodscaijiimages',$goodscaiji_info['goodscaijiimages'],'file','',0,lang('plugin/yiqixueba','goodscaijiimages_comment').$imageshtml,'','',true);//file filetext

		showsetting(lang('plugin/yiqixueba','imagessize'), array('imagewidth', 'imageheight'), array(intval($imagewidth), intval($imageheight)), 'multiply','',0,lang('plugin/yiqixueba','imagessize_comment'),'','',true);//multiply daterange

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="goodscaijidescription" style="width:700px;height:200px;visibility:hidden;">'.$goodscaiji_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="goodscaijidescription"]', {
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
		$goodscaijiname = dhtmlspecialchars(trim($_GET['goodscaijiname']));
		$goodscaijititle = strip_tags(trim($_GET['goodscaijititle']));
		$status	= intval($_GET['status']);
		$createtime	= strtotime($_GET['createtime']);
		$description = dhtmlspecialchars(trim($_GET['description']));
		$goodscaijisort = trim($_GET['goodscaijisort']);

		if(!$goodscaijiname){
			cpmsg(lang('plugin/yiqixueba','goodscaijiname_invalid'), '', 'error');
		}
		if(!ispluginkey($goodscaijiname)) {
			cpmsg(lang('plugin/yiqixueba','goodscaijiname_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['goodscaijiimages']);
		if($_FILES['goodscaijiimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['goodscaijiimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['goodscaijiimages'])) {
			$valueparse = parse_url(addslashes($_POST['goodscaijiimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['goodscaijiimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['goodscaijiimages']));
			}
			$ico = '';
		}
		$data = array(
			'goodscaijiname' => $goodscaijiname,
			'goodscaijititle' => $goodscaijititle,
			'description' => $description,
			'goodscaijiimages' => $ico,
			'goodscaijisort' => $goodscaijisort,
			'status' => $status,
			'createtime' => $createtime,
		);
		if($goodscaijiid){
			$data['updatetime'] = time();
			C::t(GM('shop_goodscaiji'))->update($goodscaijiid,$data);
		}else{
			C::t(GM('shop_goodscaiji'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_goodscaiji_succeed'), 'action='.$this_page.'&subop=goodscaijilist', 'succeed');
	}


}

?>