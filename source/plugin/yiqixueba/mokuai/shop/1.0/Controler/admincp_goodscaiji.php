<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

require_once libfile('class/xml');
$subops = array('list','edit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];
$city = getgpc('city');
$citypage = getgpc('citypage');
$lashoucitys = getlashoucity();

$cityxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_citys.xml';
$citys = xml2array(file_get_contents($cityxmlfile));

$city_select = '<select name="city">';
$kk=0;
foreach($lashoucitys as $k=>$v ){
	if($kk==0 && !$city){
		$city = $k;
	}
	$city_select .= '<option value="'.$k.'"'.($city ==$k ? ' selected' : '').'>'.$v['city'].'</option>';
	$kk++;
}
$city_select .= '</select>';

$goodsxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'.xml';
$goodss = xml2array(file_get_contents($goodsxmlfile));
$goodspages = getlashoupage($city);
$citypage = $citypage ? $citypage : 1;
$load_page0 = explode('&citypage=',$this_page);
$load_page = $_G['siteurl'].ADMINSCRIPT."?action=".$load_page0[0];
$page = max(1, $_G['page']);
$prepage = 20;
if($subop == 'list') {
	if(getgpc('caiji')){

		if(!$citys[$city]['temppage']){
			$citys[$city]['temppage'] = $citypage;
			file_put_contents ($cityxmlfile,diconv(array2xml($citys, 1),"UTF-8", $_G['charset']."//IGNORE"));
		}
		if($citys[$city]['temppage'] != $goodspages){
			$citys[$city]['temppage'] = $citypage;
			file_put_contents ($cityxmlfile,diconv(array2xml($citys, 1),"UTF-8", $_G['charset']."//IGNORE"));
			$goodss = getpagelink($city,$citypage);
			file_put_contents ($goodsxmlfile,diconv(array2xml($goodss, 1),"UTF-8", $_G['charset']."//IGNORE"));

			cpmsg(lang('plugin/yiqixueba','goods_caiji_page').$lashoucitys[$city]['city'].'&nbsp;&nbsp;'.$citypage.'&nbsp;&nbsp;('.$citypage.'/'.$goodspages.')', $load_page."&city=".$city."&citypage=".($citypage+1).'&caiji=yes','loading', array('volume' => $volume));

		}else{
			$curid = '';
			$num = 0;
			foreach($goodss as $k=>$v ){
				if(!$v['caiji']){
					$curid = $k;
					$num++;
				}
			}
			if($curid){
				$curxmlfile = round((count($goodss)-$num)/300);
				$goodss[$curid]['caiji'] = 1;
				$goodss[$curid]['curxmlfile'] = $curxmlfile;
				$goodslistxmlfile = MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'_'.$curxmlfile.'.xml';
				$goodslist = xml2array(file_get_contents($goodslistxmlfile));
				$goodslist[$curid] = getgoodscomment($curid);
				file_put_contents ($goodsxmlfile,diconv(array2xml($goodss, 1),"UTF-8", $_G['charset']."//IGNORE"));
				file_put_contents ($goodslistxmlfile,diconv(array2xml($goodslist, 1),"UTF-8", $_G['charset']."//IGNORE"));

				cpmsg(lang('plugin/yiqixueba','goods_caiji_ing').$lashoucitys[$city]['city'].'&nbsp;&nbsp;'.$goodslist[$curid]['title'].'&nbsp;&nbsp;('.(count($goodss)-$num).'/'.count($goodss).')', $load_page."&city=".$city.'&caiji=yes','loading', array('volume' => $volume));

			}
		}
	}
	if(!submitcheck('submit')) {
		if(count($goodss)>0){
			$multipage = multi(count($goodss), $prepage, $page, ADMINSCRIPT."?action=".$this_page."&page=".$page);
		}
		showtips(lang('plugin/yiqixueba','goodscaiji_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader('search');
		echo '<tr><td>';
		echo $city_select;
		echo "&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=list&city=".$city."&caiji=yes\" target=\"_blank\">".lang('plugin/yiqixueba','lashou_goods_caiji')."</a>";
		echo "&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','goodscaiji_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','goodsimg'),lang('plugin/yiqixueba','goodstitle'),lang('plugin/yiqixueba','goodsprice'),lang('plugin/yiqixueba','goodscaijisort'),lang('plugin/yiqixueba','dealtime'),lang('plugin/yiqixueba','shopname'),''));
		$goodss_row = array_slice($goodss,count($goodss)-($page)*$prepage + 1,$prepage,true);
		foreach($goodss_row as $k=>$row ){
			if(!$goodslist_row[$row['curxmlfile']]){
				$goodslist_row[$row['curxmlfile']] = xml2array(file_get_contents( MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'_'.$row['curxmlfile'].'.xml'));
			}
			$sort_text = '';
			foreach($goodslist_row[$row['curxmlfile']][$k]['fenlei'] as $ks=>$vs ){
				$sort_text .= '&nbsp;&nbsp;'.$vs['title'];
			}
			showtablerow('', array('class="td25"','class="td25"', 'class="td28"', 'class="td29"','class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$k\" />",
				'<a href="http://'.$city.'.lashou.com/deal/'.$k.'.html" target="_blank"><img src="'.$goodslist_row[$row['curxmlfile']][$k]['img'].'" width="110" height="70"/></a>',
				'<span class="bold">'.$goodslist_row[$row['curxmlfile']][$k]['title'].'</span>',
				$goodslist_row[$row['curxmlfile']][$k]['price'].'/'.$goodslist_row[$row['curxmlfile']][$k]['oldprice'],
				$sort_text,
				dgmdate($goodslist_row[$row['curxmlfile']][$k]['deal-time'],'d'),
				$goodslist_row[$row['curxmlfile']][$k]['shopname'],

				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=edit&oldid=$k\" >".lang('plugin/yiqixueba','edit')."</a>",
			));
		}
		showsubmit('submit', 'submit', 'del','',$multipage);
		showtablefooter();
		showformfooter();
	}else{
	}

}elseif($subop == 'edit') {
	$oldid = getgpc('oldid');
	$goodsinfo = xml2array(file_get_contents(MOKUAI_DIR.'/shop/1.0/Data/lashou_'.$city.'_'.$goodss[$oldid]['curxmlfile'].'.xml'));
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
	$goodssort_select = '<select name="goodssort">';
	foreach ( $gsort as $k => $v ){
		$goodssort_select .= '<option value="'.$k.'" '.($goods_info['goodssort'] == $k ? ' selected':'').'>'.$v.'</option>';
	}
	$goodssort_select .= '';
	foreach($goodsinfo[$oldid]['fenlei'] as $ks=>$vs ){
		$sort_text .= '&nbsp;&nbsp;'.$vs['title'];
	}
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','edit_goodscaiji_tips'));
		showformheader($this_page.'&subop=edit','enctype');
		showtableheader(lang('plugin/yiqixueba','goodscaiji_option'));
		showhiddenfields(array('goodscaijiid'=>$oldid));
		showsetting(lang('plugin/yiqixueba','goodscaijistatus'),'status',$goodsinfo[$oldid]['status'],'radio','',0,lang('plugin/yiqixueba','goodscaijistatus_comment'),'','',true);//radio

		showsetting(lang('plugin/yiqixueba','goodscaijiname'),'goodscaijiname',$goodsinfo[$oldid]['title'],'text','',0,lang('plugin/yiqixueba','goodscaijiname_comment'),'','',true);//text password number color

		showsetting(lang('plugin/yiqixueba','price'),'price',$goodsinfo[$oldid]['oldprice'],'text','',0,lang('plugin/yiqixueba','price_comment'),'','',true);//price

		showsetting(lang('plugin/yiqixueba','newprice'),'newprice',$goodsinfo[$oldid]['price'],'text','',0,lang('plugin/yiqixueba','newprice_comment'),'','',true);

		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		showsetting(lang('plugin/yiqixueba','dealtime'),'dealtime',dgmdate($goodsinfo[$oldid]['deal-time'],'dt'),'calendar','',0,lang('plugin/yiqixueba','createtime_comment'),'','',true);//calendar

		showsetting(lang('plugin/yiqixueba','goodssort'),'','',$goodssort_select,'',0,lang('plugin/yiqixueba','goodssort_comment').$sort_text,'','',true);


		showsetting(lang('plugin/yiqixueba','goodscaijiimages'),'','','<img src="'.$goodsinfo[$oldid]['img'].'" width="330" height="210"/>','',0,lang('plugin/yiqixueba','goodscaijiimages_comment'),'','',true);//file filetext

		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','description').':');
		echo '<tr class="noborder" ><td colspan="2" >';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<textarea name="goodscaijidescription" style="width:800px;height:900px;visibility:hidden;">'.$goodsinfo[$oldid]['comment'].'</textarea>';
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