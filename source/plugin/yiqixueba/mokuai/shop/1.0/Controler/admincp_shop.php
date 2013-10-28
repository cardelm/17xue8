<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('shoplist','shopedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];


$shopid = getgpc('shopid');
$shop_info = C::t(GM('shop_shop'))->fetch($shopid);

if($subop == 'shoplist') {
	if(!submitcheck('submit')) {
		$shenhe = intval(getgpc('shenhe'));
		$renling = intval(getgpc('renling'));
		$shopname = trim(getgpc('shopname'));
		showtips(lang('plugin/yiqixueba','brand_shop_list_tips'));
		showformheader($this_page.'&subop=shoplist');
		showtableheader();
		//每页显示条数
		$tpp = intval(getgpc('tpp')) ? intval(getgpc('tpp')) : '20';
		$select[$tpp] = $tpp ? "selected='selected'" : '';
		$tpp_options = "<option value='20' $select[20]>20</option><option value='50' $select[50]>50</option><option value='100' $select[100]>100</option>";
		//
		//////搜索内容
		echo '<tr><td>';
		$sortupid_select = '<select name="shopsort"><option value="0">顶级</option>';
		$shopsorts = C::t(GM('shop_shopsort'))->range();
		foreach($shopsorts as $k=>$row ){
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_brand_shopsort')." order by concat(upids,'-',shopsortid) asc");
		//while($row = DB::fetch($query)) {
			$sortupid_select .= '<option value="'.$row['shopsortid'].'" '.($shopsort == $row['shopsortid'] ? ' selected' :'').'>'.str_repeat("--",$row['sortlevel']-1).$row['sorttitle'].'</option>';
		}
		$sortupid_select .= '</select>';
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','shopsort').'&nbsp;&nbsp;'.$sortupid_select;
		$shenhe_select = '<select name="shenhe"><option value="">'.lang('plugin/yiqixueba','all').'</option><option value="1" '.($shenhe==1 ? ' selected':'').'>'.lang('plugin/yiqixueba','noshenhe').'</option><option value="2" '.($shenhe==2 ? ' selected':'').'>'.lang('plugin/yiqixueba','shenheed').'</option></select>';
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','shenhe').'&nbsp;&nbsp;'.$shenhe_select;
		$renling_select = '<select name="renling"><option value="">'.lang('plugin/yiqixueba','all').'</option><option value="1" '.($renling==1 ? ' selected':'').'>'.lang('plugin/yiqixueba','norenling').'</option><option value="2" '.($renling==2 ? ' selected':'').'>'.lang('plugin/yiqixueba','renlinged').'</option></select>';
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','renling').'&nbsp;&nbsp;'.$renling_select;
		//每页显示条数
		echo "&nbsp;&nbsp;".$lang['perpage']."<select name=\"tpp\">$tpp_options</select>";
		echo "&nbsp;&nbsp;".lang('plugin/yiqixueba','shortshopname').'&nbsp;&nbsp;<input type="text" name="shopname" value="'.$shopname.'" size="10">&nbsp;&nbsp;'.lang('plugin/yiqixueba','dianzhu').'&nbsp;&nbsp;<input type="text" name="dianzhu" value="'.$dianzhu.'" size="6">';
		echo "&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		//////搜索内容
		showtablefooter();

		showtableheader(lang('plugin/yiqixueba','brand_shop_list'));

		showsubtitle(array('', lang('plugin/yiqixueba','shoplogo'),lang('plugin/yiqixueba','shopinfo'),lang('plugin/yiqixueba','shoptime'), lang('plugin/yiqixueba','field_uid'), lang('plugin/yiqixueba','status'), ''));

		$get_text = '&tpp='.$tpp.'&shenhe='.$shenhe.'&renling='.$renling.'&upmokuai='.$upmokuai.'&shopname='.$shopname;
		//搜索条件处理
		$perpage = $tpp;
		$start = ($page - 1) * $perpage;
		$where = "";
		$where .= $shopsort ? " and shopsort = ".$shopsort : "";
		$where .= $shenhe ? " and status = ".($shenhe-1) : "";
		$where .= $renling ==1 ? " and uid = 0 " : ($renling ==2 ? " and uid > 0 " : "");
		$where .= $shopname ? " and shopname like '%".$shopname."%'" : "";
		$where .= $dianzhu ? " and uid =".(DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username ='".$dianzhu."'")) : "";
		if($where) {
			$where = " where ".substr($where,4,strlen($where)-4);
		}else{
			$where = " where upshopid = 0 ";
		}

		//$shopcount = DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_brand_shop').$where);
		$multi = multi($shopcount, $perpage, $page, ADMINSCRIPT."?action=".$this_page."&subop=shoplist".$get_text);

		$shops = C::t(GM('shop_shop'))->range();
		foreach($shops as $k=>$row ){
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_brand_shop').$where." order by shopid desc  limit ".$start.", ".$perpage);
		//while($row = DB::fetch($query)) {
			//$mknum = DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_brand_shop')." WHERE upshopid=".$row['shopid']);

			$shoplogo = '';
			if($row['shoplogo']!='') {
				$shoplogo = str_replace('{STATICURL}', STATICURL, $row['shoplogo']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $shoplogo) && !(($valueparse = parse_url($shoplogo)) && isset($valueparse['host']))) {
					$shoplogo = $_G['setting']['attachurl'].'common/'.$row['shoplogo'].'?'.random(6);
				}
				$shoplogo = '<img src="'.$shoplogo.'" width="80" height="60"/>';
			}
			$shoplogo = $shoplogo ? $shoplogo : '<img src="source/plugin/yiqixueba/template/yiqixueba/default/style/image/noshoplogo.jpg" width="80" height="60"/>';

			$sssort_title_text = $sssort = '';
			$sssort = $sort_data[$row['shopsort']]['upids'] ? $sort_data[$row['shopsort']]['upids'].'-'.$row['shopsort'] : $row['shopsort'];
			$sssort_array = explode("-",$sssort);
			$sssort_title_array = array();
			foreach ($sssort_array as $v){
				if ($v){
					$sssort_title_array[] = $sort_data[$v]['sorttitle'];
				}
			}
			$sssort_title_text = implode(">>",$sssort_title_array);

			showtablerow('', array('class="td25"','class="td23"','class="td28"', 'class="td28"', 'class="td23"','class="td25"',''), array(
				($mknum ?'<a href="javascript:;" class="right" onclick="toggle_group(\'menu_'.$row['shopid'].'\', this)">[+]</a>':'')."<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[shopid]\">",
				$shoplogo,
				'<a href="plugin.php?id=yiqixueba&submod=shopdisplay&shopid='.$row['shopid'].'" target="_blank">'.$row['shopname'].'</a><br />'.$sssort_title_text.'<br />'.$row['shopdistrict'],
				lang('plugin/yiqixueba','create').($row['createtime'] ? dgmdate($row['createtime'],'dt') : '').'<br />'.lang('plugin/yiqixueba','renling').($row['renlingtime'] ? dgmdate($row['renlingtime'],'dt') : ''),
				$row['uid'] ? '<a href="home.php?mod=space&uid='.intval($row['uid']).'" target="_blank">'.DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$row['uid']).'</a>' : lang('plugin/yiqixueba','norenling'),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['shopid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&shopid=$row[shopid]\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&upshopid=$row[shopid]\" class=\"act\">".lang('plugin/yiqixueba','fendian')."</a><br /><br /><a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&upshopid=$row[shopid]\" class=\"act\">".lang('plugin/yiqixueba','chanpin')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&upshopid=$row[shopid]\" class=\"act\">".lang('plugin/yiqixueba','dianping')."</a>",
			));

			//$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_brand_shop')." WHERE upshopid=".$row['shopid']);
			$kk = 0;
			showtagheader('tbody', 'menu_'.$row['shopid'], false);
			while($row1 = DB::fetch($query1)) {
			$shoplogo = '';
				if($row1['shoplogo']!='') {
					$shoplogo1 = str_replace('{STATICURL}', STATICURL, $row1['shoplogo']);
					if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $shoplogo1) && !(($valueparse = parse_url($shoplogo1)) && isset($valueparse['host']))) {
						$shoplogo1 = $_G['setting']['attachurl'].'common/'.$row1['shoplogo'].'?'.random(6);
					}
					$shoplogo1 = '<img src="'.$shoplogo1.'" width="80" height="60"/>';
				}
				$shoplogo1 = $shoplogo1 ? $shoplogo1 : '<img src="source/plugin/yiqixueba/template/yiqixueba/default/style/image/noshoplogo.jpg" width="80" height="60"/>';
				$sssort_title_text = $sssort = '';
				$sssort = $sort_data[$row['shopsort']]['upids'] ? $sort_data[$row['shopsort']]['upids'].'-'.$row['shopsort'] : $row['shopsort'];
				$sssort_array = explode("-",$sssort);
				$sssort_title_array = array();
				foreach ($sssort_array as $v){
					if ($v){
						$sssort_title_array[] = $sort_data[$v]['sorttitle'];
					}
				}
				$sssort_title_text = implode(">>",$sssort_title_array);
				showtablerow('', array('class="td25"','class="td23"','class="td28"', 'class="td28"', 'class="td23"','class="td25"',''), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row1[shopid]\">",
					"<div class=\"".($kk == $mknum ? 'board' : 'lastboard')."\">&nbsp;".$shoplogo1,
					'<a href="plugin.php?id=yiqixueba&submod=shopdisplay&shopid='.$row1['shopid'].'" target="_blank">'.$row1['shopname'].'</a><br />'.$sssort_title_text.'<br />'.$row1['shopdistrict'],
					lang('plugin/yiqixueba','create').($row1['createtime'] ? dgmdate($row1['createtime'],'dt') : '').'<br />'.lang('plugin/yiqixueba','renling').($row1['renlingtime'] ? dgmdate($row1['renlingtime'],'dt') : ''),
					$row1['uid'] ? '<a href="home.php?mod=space&uid='.intval($row1['uid']).'" target="_blank">'.DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$row1['uid']).'</a>' : lang('plugin/yiqixueba','norenling'),
					"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row1['brand_shopid']."]\" value=\"1\" ".($row1['status'] > 0 ? 'checked' : '').">",
					"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopedit&shopid=$row1[shopid]&upmokuai=$row[upmokuai]\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a>",
				));
				$kk++;
			}
			showtagfooter('tbody');
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=shopedit&upmokuai='.$upmokuai.'" class="addtr">'.lang('plugin/yiqixueba','add_shop').'</a></div></td></tr>';
		showsubmit('submit','submit','del','',$multi);
		showtablefooter();
		showformfooter();
	}else{
		$deletes = getgpc('delete');
		if(is_array($deletes)){
			foreach($deletes as $k=>$v ){
				DB::delete('yiqixueba_brand_shop',array('shopid'=>$v));
				DB::delete('yiqixueba_brand_business',array('shopid'=>$v));
			}
		}
		cpmsg(lang('plugin/yiqixueba', 'shop_edit_succeed'), 'action='.$this_page.'&subop=shoplist', 'succeed');
	}
}elseif($subop == 'shopedit') {

	if(!submitcheck('submit')) {
		require_once libfile('function/profile');
		$shoplogo = '';
		if($shop_info['shoplogo']!='') {
			$shoplogo = str_replace('{STATICURL}', STATICURL, $shop_info['shoplogo']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $shoplogo) && !(($valueparse = parse_url($shoplogo)) && isset($valueparse['host']))) {
				$shoplogo = $_G['setting']['attachurl'].'common/'.$shop_info['shoplogo'].'?'.random(6);
			}
			$shoplogohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$shoplogo.'" width="80" height="60"/>';
		}
		$sortupid_select = '<select name="shopsort"><option value="0">顶级</option>';
		$shopsorts = C::t(GM('shop_shopsort'))->range();
		foreach($shopsorts as $k=>$row ){
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_brand_shopsort')." order by concat(upids,'-',shopsortid) asc");
		//while($row = DB::fetch($query)) {
			$sortupid_select .= '<option value="'.$row['shopsortid'].'" '.($shop_info['shopsort'] == $row['shopsortid'] ? ' selected' :'').'>'.str_repeat("--",$row['sortlevel']-1).$row['sorttitle'].'</option>';
		}
		$sortupid_select .= '</select>';
		$shoplocation = explode(',',$shop_info['shoplocation']);
		$dist_option = '';
		//$shopsorts = C::t('common_district')->range();
		//foreach($shopsorts as $k=>$row ){
		$query = DB::query("SELECT * FROM ".DB::table('common_district')." WHERE upid = 73 order by displayorder asc");
		while($row = DB::fetch($query)) {
			$dist_option .= '<option value="'.$row['id'].'" '.($shop_info['dist'] == $row['name'] ? ' selected':'').'>'.$row['name'].'</option>';
		}
		$siat_select = '';
		if($shop_info['dist']){
			$dist = DB::result_first("SELECT id FROM ".DB::table('common_district')." WHERE name='".$shop_info['dist']."'");
			//dump($dist);
			$siat_select .= '<select name="dist2">';
			$query = DB::query("SELECT * FROM ".DB::table('common_district')." WHERE upid = $dist order by displayorder asc");
			while($row = DB::fetch($query)) {
				$siat_select .= '<option value="'.$row['id'].'" '.($shop_info['comy'] == $row['name'] ? ' selected':'').'>'.$row['name'].'</option>';
			}
			$siat_select .='</select>';
		}
		showtips(lang('plugin/yiqixueba','brand_shop_edit_tips'));
		showformheader($this_page.'&subop=shopedit','enctype');
		showtableheader(lang('plugin/yiqixueba','shop_edit'));
		$shopid ? showhiddenfields(array('shopid'=>$shopid)) : '';
		$upmokuai ? showhiddenfields(array('upmokuai'=>$upmokuai)) : '';
		$upshopid ? showhiddenfields(array('upshopid'=>$upshopid)) : '';
		showsetting(lang('plugin/yiqixueba','shopname'),'shop_info[shopname]',$shop_info['shopname'],'text','',0,lang('plugin/yiqixueba','shopname_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shoplogo'),'shoplogo',$shop_info['shoplogo'],'filetext','',0,lang('plugin/yiqixueba','shoplogo_comment').$shoplogohtml,'','',true);
		showsetting(lang('plugin/yiqixueba','dianzhu'),'shop_info[uid]',$shop_info['uid'],'text','',0,lang('plugin/yiqixueba','dianzhu_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shopdistrict'),'shop_info[shopdistrict]',$shop_info['shopdistrict'],'<select name="dist1"  id="dist1" onchange="ajaxget(\'plugin.php?id=yiqixueba&submod=ajax&ajaxtype=getdist&dist=\'+ this.value, \'nextdist\');">'.$dist_option.'</select><span id="nextdist" class="xi1">'.$siat_select.'</span>','',0,lang('plugin/yiqixueba','shopdistrict_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shopsort'),'','',$sortupid_select,'',0,lang('plugin/yiqixueba','shopsort_comment'),'','',true);
		//showsetting(lang('plugin/yiqixueba','shopalias'),'shop_info[shopalias]',$shop_info['shopalias'],'text','',0,lang('plugin/yiqixueba','shopalias_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','address'),'shop_info[address]',$shop_info['address'],'text','',0,lang('plugin/yiqixueba','address_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','phone'),'shop_info[phone]',$shop_info['phone'],'text','',0,lang('plugin/yiqixueba','phone_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','lianxiren'),'shop_info[lianxiren]',$shop_info['lianxiren'],'text','',0,lang('plugin/yiqixueba','lianxiren_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','qq'),'shop_info[qq]',$shop_info['qq'],'text','',0,lang('plugin/yiqixueba','qq_comment'),'','',true);
		//showsetting(lang('plugin/yiqixueba','shopvideo'),'shop_info[shopvideo]',$shop_info['shopvideo'],'text','',0,lang('plugin/yiqixueba','shopvideo_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shopintroduction'),'shop_info[shopintroduction]',$shop_info['shopintroduction'],'textarea','',0,lang('plugin/yiqixueba','shopintroduction_comment'),'','',true);
		echo '<input type="hidden" name="baidu_x" id="baidu_x" value="'.$shoplocation[0].'">';
		echo '<input type="hidden" name="baidu_y" id="baidu_y" value="'.$shoplocation[1].'">';
		echo '<tr class="noborder" ><td colspan="2" class="td27" s="1">'.lang('plugin/yiqixueba','shoplocation').':</td></tr>';
		echo '<tr class="noborder" ><td colspan="2" ><iframe id="baidumapboa" src="plugin.php?id=yiqixueba&submod=shop_baidumap" width="600" height="400" frameborder="0" ></iframe></td></tr>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/editor.js" type="text/javascript"></script>';
		echo '<tr class="noborder" ><td colspan="2" class="td27" s="1">'.lang('plugin/yiqixueba','shopinformation').':</td></tr>';
		echo '<tr class="noborder" ><td colspan="2" ><textarea name="shopinformation" style="width:700px;height:200px;visibility:hidden;">'.$shop_info['shopinformation'].'</textarea></td></tr>';
		//showsetting(lang('plugin/yiqixueba','shoprecommend'),'shop_info[shoprecommend]',$shop_info['shoprecommend'],'text','',0,lang('plugin/yiqixueba','shoprecommend_comment'),'','',true);
		//showsetting(lang('plugin/yiqixueba','shoptemplate'),'shop_info[shoptemplate]',$shop_info['shoptemplate'],'select','',0,lang('plugin/yiqixueba','shoptemplate_comment'),'','',true);
		//showsetting(lang('plugin/yiqixueba','shoplevel'),'shop_info[shoplevel]',$shop_info['shoplevel'],'text','',0,lang('plugin/yiqixueba','shoplevel_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','status'),'shop_info[status]',$shop_info['status'],'radio','',0,lang('plugin/yiqixueba','shopstatus_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{

		if(!htmlspecialchars(trim($_GET['shop_info']['shopname']))) {
			cpmsg(lang('plugin/yiqixueba','shopname_nonull','error'));
		}
		$shoplogo = addslashes($_GET['shoplogo']);
		if($_FILES['shoplogo']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['shoplogo'], 'common') && $upload->save()) {
				$shoplogo = $upload->attach['attachment'];
			}
		}
		if($_POST['delete'] && addslashes($_POST['shoplogo'])) {
			$valueparse = parse_url(addslashes($_POST['shoplogo']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['shoplogo']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'temp/'.addslashes($_POST['shoplogo']));
			}
			$shoplogo = '';
		}
		$data = array();
		$datas = $_GET['shop_info'];
		$datas['upmokuai'] = intval($_GET['upmokuai']);
		$datas['upshopid'] = intval($_GET['upshopid']);
		$datas['shopsort'] = intval($_GET['shopsort']);
		$datas['shoplogo'] = $shoplogo;
		$datas['dist'] = DB::result_first("SELECT name FROM ".DB::table('common_district')." WHERE id=".intval($_GET['dist1']));
		$datas['comy'] = DB::result_first("SELECT name FROM ".DB::table('common_district')." WHERE id=".intval($_GET['dist2']));
		$datas['shopinformation'] = stripslashes($_POST['shopinformation']);
		$datas['shoplocation'] = implode(",",array(trim(getgpc('baidu_x')),trim(getgpc('baidu_y'))));
		$data['createtime'] = time();
		foreach ( $datas as $k=>$v) {
			$data[$k] = htmlspecialchars(trim($v));
			//if(!DB::result_first("describe ".DB::table('yiqixueba_brand_shop')." ".$k)) {
				//$sql = "alter table ".DB::table('yiqixueba_brand_shop')." add `".$k."` varchar(255) not Null;";
				//runquery($sql);
			//}
		}
		//dump($data);
		if($shopid) {
			C::t(GM('shop_shop'))->update($shopid,$data);
		}else{
			C::t(GM('shop_shop'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba', 'shop_edit_succeed'), 'action='.$this_page.'&subop=shoplist', 'succeed');
	}
}

?>