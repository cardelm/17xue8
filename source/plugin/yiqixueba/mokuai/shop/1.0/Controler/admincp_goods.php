<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$goodssorts = api_indata('server_goodssort');
$goodssorts = array_sort($goodssorts,'displayorder');
foreach ( $goodssorts as $k => $v ){
	if($v['sortupid']==0){
		foreach ($goodssorts  as $k1 => $v1 ){
			if($v1['sortupid'] == $v['sortname']){
				$gsort[$v1['sortname']] = $v['sorttitle'].'--'.$v1['sorttitle'];
			}
		}
	}
}

$goodsid = getgpc('goodsid');
$goods_info = C::t(GM('shop_goods'))->fetch($goodsid);

if($subop == 'list') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','goods_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','goods_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','goodsname'),lang('plugin/yiqixueba','goodstitle'),lang('plugin/yiqixueba','goodssort'),lang('plugin/yiqixueba','createtime'),lang('plugin/yiqixueba','status'),''));
		$goodss_row = C::t(GM('shop_goods'))->range();
		foreach($goodss_row as $k=>$row ){
			showtablerow('', array('class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[goodsid]\" />",
				'<span class="bold">'.$row['goodsname'].'</span>',
				$row['goodstitle'],
				$gsort[$row['goodssort']],
				dgmdate($row['youxiaotime'],'d'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['goodsid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&goodsid=$row[goodsid]\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=edit" class="addtr" >'.lang('plugin/yiqixueba','add_new_goods').'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();
	}else{
		if($_GET['delete']) {
			C::t(GM('shop_goods'))->delete($_GET['delete']);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_goods_succeed'), 'action='.$this_page.'&subop=goodslist', 'succeed');
	}

}elseif($subop == 'edit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_goods_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','goods_option'));
		$images = '';
		if($goods_info['goodsimages']!='') {
			$images = str_replace('{STATICURL}', STATICURL, $goods_info['goodsimages']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $images) && !(($valueparse = parse_url($images)) && isset($valueparse['host']))) {
				$images = $_G['setting']['attachurl'].'common/'.$goods_info['goodsimages'].'?'.random(6);
			}
			$imageshtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$images.'" width="40" height="40"/>';
		}
		$goodsid ? showhiddenfields(array('goodsid'=>$goodsid)) : '';

		showsetting(lang('plugin/yiqixueba','goodsstatus'),'status',$goods_info['status'],'radio','',0,lang('plugin/yiqixueba','goodsstatus_comment'),'','',true);//是否有效

		showsetting(lang('plugin/yiqixueba','goodsname'),'goodsname',$goods_info['goodsname'],'text',$goodsid ?'readonly':'',0,lang('plugin/yiqixueba','goodsname_comment'),'','',true);//商品名称

		showsetting(lang('plugin/yiqixueba','shopid'),'shopid',$goods_info['shopid'],'text',$goodsid ?'readonly':'',0,lang('plugin/yiqixueba','shopid_comment'),'','',true);//所属商家

		showsetting(lang('plugin/yiqixueba','goodstitle'),'goodstitle',$goods_info['goodstitle'],'textarea','',0,lang('plugin/yiqixueba','goodstitle_comment'),'','',true);//商品标题

		showsetting(lang('plugin/yiqixueba','price'),'price',$goods_info['price'],'text','',0,lang('plugin/yiqixueba','price_comment'),'','',true);//原价

		showsetting(lang('plugin/yiqixueba','newprice'),'newprice',$goods_info['newprice'],'text','',0,lang('plugin/yiqixueba','newprice_comment'),'','',true);//折扣价

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','youxiaotime'),'youxiaotime',$goods_info['youxiaotime'] ? dgmdate($goods_info['youxiaotime'],'d') : dgmdate(time()+2592000,'d'),'calendar','',0,lang('plugin/yiqixueba','youxiaotime_comment'),'','',true);//到期日期


		$goodssort_select = '<select name="goodssort">';
		foreach ( $gsort as $k => $v ){
			$goodssort_select .= '<option value="'.$k.'" '.($goods_info['goodssort'] == $k ? ' selected':'').'>'.$v.'</option>';
		}
		$goodssort_select .= '';

		showsetting(lang('plugin/yiqixueba','goodssort'),'','',$goodssort_select,'',0,lang('plugin/yiqixueba','goodssort_comment'),'','',true);//商品分类


		showsetting(lang('plugin/yiqixueba','goodsimages'),'goodsimages',$goods_info['goodsimages'],'file','',0,lang('plugin/yiqixueba','goodscaijiimages_comment').$imageshtml,'','',true);//商品封面图片

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="goodsdescription" style="width:800px;height:900px;visibility:hidden;">'.$goods_info['description'].'</textarea>';
		echo '</td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="goodsdescription"]', {
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
		$goodsname = dhtmlspecialchars(trim($_GET['goodsname']));
		$goodstitle = strip_tags(trim($_GET['goodstitle']));
		$status	= intval($_GET['status']);
		$newprice	= intval($_GET['newprice']);
		$shopid	= intval($_GET['shopid']);
		$price	= intval($_GET['price']);
		$youxiaotime = strtotime($_GET['youxiaotime']);
		$description = dhtmlspecialchars(trim($_GET['goodsdescription']));
		$goodssort = trim($_GET['goodssort']);

		if(!$goodsname){
			dump('no');
			cpmsg(lang('plugin/yiqixueba','goodsname_invalid'), '', 'error');
		}
		if(ispluginkey($goodsname)) {
			dump('sql');
			cpmsg(lang('plugin/yiqixueba','goodsname_invalid'), '', 'error');
		}
		$ico = addslashes($_GET['goodsimages']);
		if($_FILES['goodsimages']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['goodsimages'], 'common') && $upload->save()) {
				$ico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['goodsimages'])) {
			$valueparse = parse_url(addslashes($_POST['goodsimages']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['goodsimages']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'common/'.addslashes($_POST['goodsimages']));
			}
			$ico = '';
		}
		$data = array(
			'shopid' => $shopid,
			'goodsname' => $goodsname,
			'goodstitle' => $goodstitle,
			'description' => $description,
			'goodsimages' => $ico,
			'goodssort' => $goodssort,
			'status' => $status,
			'newprice' => $newprice,
			'price' => $price,
			'youxiaotime' => $youxiaotime,
		);
		if($goodsid){
			$data['updatetime'] = time();
			C::t(GM('shop_goods'))->update($goodsid,$data);
		}else{
			//$data['createtime'] => time();
			C::t(GM('shop_goods'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba','edit_goods_succeed'), 'action='.$this_page.'&subop=goodslist', 'succeed');
	}


}

?>