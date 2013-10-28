<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('shopgrouplist','shopgroupedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$shopgroupid = getgpc('shopgroupid');
$shopgroup_info = C::t(GM('shop_shopgroup'))->fetch($shopgroupid);

if($subop == 'shopgrouplist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','shopgroup_list_tips'));
		showformheader($this_page.'&subop=shopgrouplist');
		showtableheader(lang('plugin/yiqixueba','shopgroup_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','shopgroupname'),lang('plugin/yiqixueba','shopnum'), lang('plugin/yiqixueba','shopgroupquanxian'), lang('plugin/yiqixueba','status'), ''));
		$shopgroups = C::t(GM('shop_shopgroup'))->range();
		foreach($shopgroups as $k=>$row ){
			$shopgroupico = '';
			if($row['shopgroupico']!='') {
				$shopgroupico = str_replace('{STATICURL}', STATICURL, $row['shopgroupico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $shopgroupico) && !(($valueparse = parse_url($shopgroupico)) && isset($valueparse['host']))) {
					$shopgroupico = $_G['setting']['attachurl'].'common/'.$row['shopgroupico'].'?'.random(6);
				}
				$shopgroupico = '<img src="'.$shopgroupico.'" width="40" height="40"/><br />';
			}else{
				$shopgroupico = '';
			}
			showtablerow('', array('class="td25"','class="td23"', 'class="td25"', 'class="td28"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[shopgroupid]\">",
				$shopgroupico.$row['shopgroupname'],
				//DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_brand_shop')." WHERE shopgroupid=".$row['shopgroupid']),
				'',
				lang('plugin/yiqixueba','inshoufei').':'.$row['inshoufei'].'&nbsp;&nbsp;'.lang('plugin/yiqixueba','inshoufeiqixian').':'.$row['inshoufeiqixian'],
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['shopgroupid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopgroupedit&shopgroupid=$row[shopgroupid]\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=shopgroupedit" class="addtr">'.lang('plugin/yiqixueba','add_shopgroup').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subop == 'shopgroupedit') {
	if(!submitcheck('submit')) {
		if($shopgroup_info['shopgroupico']!='') {
			$shopgroupico = str_replace('{STATICURL}', STATICURL, $shopgroup_info['shopgroupico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $shopgroupico) && !(($valueparse = parse_url($shopgroupico)) && isset($valueparse['host']))) {
				$shopgroupico = $_G['setting']['attachurl'].'common/'.$shopgroup_info['shopgroupico'].'?'.random(6);
			}
			$shopgroupicohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete1" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$shopgroupico.'" width="80" height="60"/>';
		}
		if($shopgroup_info['contractsample']!='') {
			$contractsamplehtml = '<label><input type="checkbox" class="checkbox" name="delete2" value="yes" /> '.$lang['del'].'</label><br /><a href="source/plugin/yiqixueba/data/'.$shopgroup_info['contractsample'].'">'.lang('plugin/yiqixueba','contractsample').'</a><br />';
		}
		showtips(lang('plugin/yiqixueba','shopgroup_edit_tips'));
		showformheader($this_page.'&subop=shopgroupedit','enctype');
		showtableheader(lang('plugin/yiqixueba','shopgroup_edit'));
		$shopgroupid ? showhiddenfields(array('shopgroupid'=>$shopgroupid)) : '';

		showsetting(lang('plugin/yiqixueba','shopgroupico'),'shopgroupico',$shopgroup_info['shopgroupico'],'filetext','','',lang('plugin/yiqixueba','shopgroupico_comment').$shopgroupicohtml,'','',true);

		showsetting(lang('plugin/yiqixueba','shopgroupname'),'shopgroup_info[shopgroupname]',$shopgroup_info['shopgroupname'],'text','',0,lang('plugin/yiqixueba','shopgroupname_comment'),'','',true);

		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<tr class="noborder" ><td colspan="2" class="td27" s="1">'.lang('plugin/yiqixueba','shopgroupdescription').':</td></tr>';
		echo '<tr class="noborder" ><td colspan="2" ><textarea name="shopgroupdescription" style="width:700px;height:200px;visibility:hidden;">'.$shopgroup_info['shopgroupdescription'].'</textarea></td></tr>';

		showsetting(lang('plugin/yiqixueba','isshop'),'shopgroup_info[isshop]',$shopgroup_info['isshop'],'radio','',0,lang('plugin/yiqixueba','isshop_comment'),'','',true);

		showsetting(lang('plugin/yiqixueba','inshoufei'),'shopgroup_info[inshoufei]',$shopgroup_info['inshoufei'],'text','',0,lang('plugin/yiqixueba','inshoufei_comment'),'','',true);

		showsetting(lang('plugin/yiqixueba','inshoufeiqixian'),'shopgroup_info[inshoufeiqixian]',$shopgroup_info['inshoufeiqixian'],'text','',0,lang('plugin/yiqixueba','inshoufeiqixian_comment'),'','',true);

		showsetting(lang('plugin/yiqixueba','enshopnum'),'shopgroup_info[enshopnum]',$shopgroup_info['enshopnum'] ? $shopgroup_info['enshopnum'] : '1' ,'text','',0,lang('plugin/yiqixueba','enshopnum_comment'),'','',true);

		showsetting(lang('plugin/yiqixueba','enfendian'),'shopgroup_info[enfendian]',$shopgroup_info['enfendian'],'radio','',0,lang('plugin/yiqixueba','enfendian_comment'),'','',true);

		//店员权限
		$dianzhang = dunserialize($shopgroup_info['dianzhang']);
		$dianzhang_array = array();
		$dianyuan_array = array('viewmember','viewxiaofei');
		foreach ( $dianyuan_array as $k => $v ){
			$dianzhang_array[] = array($v,lang('plugin/yiqixueba','dianyuan_'.$v));
		}
		showsetting(lang('plugin/yiqixueba','dianzhang'), array('dianzhang', $dianzhang_array), $dianzhang, 'mcheckbox','',0,lang('plugin/yiqixueba','dianzhang_comment'),'','',true);
		$caiwu = dunserialize($shopgroup_info['caiwu']);
		$caiwu_array = array();
		foreach ( $dianyuan_array as $k => $v ){
			$caiwu_array[] = array($v,lang('plugin/yiqixueba','dianyuan_'.$v));
		}
		showsetting(lang('plugin/yiqixueba','caiwu'), array('caiwu', $caiwu_array), $caiwu, 'mcheckbox','',0,lang('plugin/yiqixueba','caiwu_comment'),'','',true);
		$shouyin = dunserialize($shopgroup_info['shouyin']);
		$shouyin_array = array();
		foreach ( $dianyuan_array as $k => $v ){
			$shouyin_array[] = array($v,lang('plugin/yiqixueba','dianyuan_'.$v));
		}
		showsetting(lang('plugin/yiqixueba','shouyin'), array('shouyin', $shouyin_array), $shouyin, 'mcheckbox','',0,lang('plugin/yiqixueba','shouyin_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','dianyuanshenhe'),'shopgroup_info[dianyuanshenhe]',$shopgroup_info['dianyuanshenhe'],'radio','',0,lang('plugin/yiqixueba','dianyuanshenhe_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','contractsample'),'contractsample',$shopgroup_info['contractsample'],'filetext','',0,$contractsamplehtml.lang('plugin/yiqixueba','contractsample_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','status'),'shopgroup_info[status]',$shopgroup_info['status'],'radio','',0,lang('plugin/yiqixueba','status_comment'),'','',true);

		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="shopgroupdescription"]', {
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
	}else{
		if(!htmlspecialchars(trim($_GET['shopgroup_info']['shopgroupname']))) {
			cpmsg(lang('plugin/yiqixueba','shopgroupname_nonull'));
		}
		$shopgroupico = addslashes($_POST['shopgroupico']);
		if($_FILES['shopgroupico']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['shopgroupico'], 'common') && $upload->save()) {
				$shopgroupico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete1'] && addslashes($_POST['shopgroupico'])) {
			$valueparse = parse_url(addslashes($_POST['shopgroupico']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['shopgroupico']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'temp/'.addslashes($_POST['shopgroupico']));
			}
			$shopgroupico = '';
		}
		$contractsample = addslashes($_POST['contractsample']);
		if($contractsample && $_FILES['contractsample']) {
			if($_FILES['contractsample']['error']){
				cpmsg('文件错误');
			}
			if($_FILES['contractsample']['type']=='application/msword'){
				$contractsample = htmlspecialchars(trim($_GET['shopgroup_info']['shopgroupname'])).time().substr($_FILES['contractsample']['name'],intval(strrpos($_FILES['contractsample']['name'],".")));
				move_uploaded_file($_FILES['contractsample']['tmp_name'], "source/plugin/yiqixueba/data/" . $contractsample);
			}
		}
		if($_POST['delete2'] && addslashes($_POST['contractsample'])) {
				@unlink('source/plugin/yiqixueba/data/'.addslashes($_POST['contractsample']));
			$$contractsample = '';
		}
		$data = array();
		$datas = $_GET['shopgroup_info'];
		$datas['shopgroupico'] = $shopgroupico;
		$datas['contractsample'] = $contractsample;
		$datas['xiaofei'] = serialize($_GET['xiaofei']);;
		$datas['dianzhang'] = serialize($_GET['dianzhang']);;
		$datas['caiwu'] = serialize($_GET['caiwu']);;
		$datas['shouyin'] = serialize($_GET['shouyin']);;
		$datas['shopgroupdescription'] = stripslashes($_POST['shopgroupdescription']);
		foreach ( $datas as $k=>$v) {
			if(in_array($k,array('xiaofei','dianzhang','caiwu','shouyin'))){
				$data[$k] = trim($v);
			}else{
				$data[$k] = htmlspecialchars(trim($v));
			}
			//if(!DB::result_first("describe ".DB::table('yiqixueba_shop_group')." ".$k)) {
				//$sql = "alter table ".DB::table('yiqixueba_shop_group')." add `".$k."` varchar(255) not Null;";
				//runquery($sql);
			//}
		}
		if($shopgroupid) {
			C::t(GM('shop_shopgroup'))->update($shopgroupid,$data);
		}else{
			C::t(GM('shop_shopgroup'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba', 'shopgroup_edit_succeed'), 'action='.$this_page.'&subop=shopgrouplist', 'succeed');
	}

}
?>