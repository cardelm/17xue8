<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('cardcatlist','cardcatedit','makecard','cardimport','cardexport','cardlist','cardedit','ajaxbind');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$cardcatid = getgpc('cardcatid');
$cardcat_info = $cardcatid ? DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_yikatong_cardcat')." WHERE cardcatid=".$cardcatid) : array();
$cardcat_info['cardkaishi'] = $cardcat_info['cardkaishi'] ? dgmdate($cardcat_info['cardkaishi'], 'd') : '';
$cardcat_info['cardyouxiaoqi'] = $cardcat_info['cardyouxiaoqi'] ? dgmdate($cardcat_info['cardyouxiaoqi'], 'd'):'';
$cardcat_info['cardqingling'] = $cardcat_info['cardqingling'] ? dgmdate($cardcat_info['cardqingling'], 'd'):'';



if($subop == 'cardcatlist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','cardcat_list_tips'));
		showformheader($this_page.'&subop=cardcatlist');
		showtableheader(lang('plugin/yiqixueba','cardcat_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','cardcatname'),lang('plugin/yiqixueba','cardtype'), lang('plugin/yiqixueba','cardjifen'),lang('plugin/yiqixueba','cardyouxiaoqi'),lang('plugin/yiqixueba','cardnum'), lang('plugin/yiqixueba','status'), ''));
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_yikatong_cardcat')." order by cardcatid asc");
		while($row = DB::fetch($query)) {
			$cardcatico = '';
			if($row['cardcatico']!='') {
				$cardcatico = str_replace('{STATICURL}', STATICURL, $row['cardcatico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $cardcatico) && !(($valueparse = parse_url($cardcatico)) && isset($valueparse['host']))) {
					$cardcatico = $_G['setting']['attachurl'].'common/'.$row['cardcatico'].'?'.random(6);
				}
				$cardcatico = '<img src="'.$cardcatico.'" width="85" height="54"/>';
			}else{
				$cardcatico = '';
			}
			$row['cardkaishi'] = $row['cardkaishi'] ? dgmdate($row['cardkaishi'], 'd') : '';
			$row['cardyouxiaoqi'] = $row['cardyouxiaoqi'] ? dgmdate($row['cardyouxiaoqi'], 'd'):'';
			$row['cardqingling'] = $row['cardqingling'] ? dgmdate($row['cardqingling'], 'd'):'';
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td25"','class="td23"','class="td25"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[cardcatid]\">",
				$cardcatico.'<br />'.$row['cardcatname'],
				lang('plugin/yiqixueba',$row['cardtype']),
				lang('plugin/yiqixueba','cardjine').':'.$row['cardjine'].'<br />'.lang('plugin/yiqixueba','cardjifen').':'.$row['cardjifen'].'<br />'.lang('plugin/yiqixueba','cardpice').':'.$row['cardpice'],
				lang('plugin/yiqixueba','kaishi').':'.$row['cardkaishi'].'<br />'.
				lang('plugin/yiqixueba','jieshu').':'.$row['cardyouxiaoqi'].'<br />'.
				lang('plugin/yiqixueba','qingling').':'.$row['cardqingling'],
				DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_yikatong_card')." WHERE cardcatid=".$row['cardcatid']),
				"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['cardcatid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=cardcatedit&cardcatid=$row[cardcatid]\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a><br /><a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=makecard&cardcatid=$row[cardcatid]\" class=\"act\">".lang('plugin/yiqixueba','makecard')."</a><br /><a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=cardimport&cardcatid=$row[cardcatid]\" class=\"act\">".lang('plugin/yiqixueba','import')."</a><br /><a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=cardexport&cardcatid=$row[cardcatid]\" class=\"act\">".lang('plugin/yiqixueba','export')."</a><br /><a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=cardlist&cardcatid=$row[cardcatid]\" class=\"act\">".lang('plugin/yiqixueba','view')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=cardcatedit" class="addtr">'.lang('plugin/yiqixueba','add_cardcat').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subop == 'cardcatedit') {
	if(!submitcheck('submit')) {
		if($cardcat_info['cardcatico']!='') {
			$cardcatico = str_replace('{STATICURL}', STATICURL, $cardcat_info['cardcatico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $cardcatico) && !(($valueparse = parse_url($cardcatico)) && isset($valueparse['host']))) {
				$cardcatico = $_G['setting']['attachurl'].'common/'.$cardcat_info['cardcatico'].'?'.random(6);
			}
			$cardcaticohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete1" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$cardcatico.'" width="85" height="54"/>';
		}
		$cardtype_select = '<select name="cardcat_info[cardtype]"><option value="benwangka" '.($cardcat_info['cardtype'] == 'benwangka' ? ' selected': '').'>'.lang('plugin/yiqixueba','benwangka').'</option><option value="quanwangka" '.($cardcat_info['cardtype'] == 'quanwangka' ? ' selected': '').'>'.lang('plugin/yiqixueba','quanwangka').'</option><option value="xunika" '.($cardcat_info['cardtype'] == 'xunika' ? ' selected': '').'>'.lang('plugin/yiqixueba','xunika').'</option></select>';
		showtips(lang('plugin/yiqixueba','cardcat_edit_tips'));
		showformheader($this_page.'&subop=cardcatedit','enctype');
		showtableheader(lang('plugin/yiqixueba','cardcat_edit'));
		$cardcatid ? showhiddenfields($hiddenfields = array('cardcatid'=>$cardcatid)) : '';
		showsetting(lang('plugin/yiqixueba','cardcatico'),'cardcatico',$cardcat_info['cardcatico'],'filetext','','',lang('plugin/yiqixueba','cardcatico_comment').$cardcaticohtml,'','',true);
		showsetting(lang('plugin/yiqixueba','cardcatname'),'cardcat_info[cardcatname]',$cardcat_info['cardcatname'],'text','',0,lang('plugin/yiqixueba','cardcatname_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardtype'),'','',$cardtype_select,'',0,lang('plugin/yiqixueba','cardtype_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardjine'),'cardcat_info[cardjine]',$cardcat_info['cardjine'],'text','',0,lang('plugin/yiqixueba','cardjine_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardjifen'),'cardcat_info[cardjifen]',$cardcat_info['cardjifen'],'text','',0,lang('plugin/yiqixueba','cardjifen_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardpice'),'cardcat_info[cardpice]',$cardcat_info['cardpice'],'text','',0,lang('plugin/yiqixueba','cardpice_comment'),'','',true);
		echo '<script src="static/js/calendar.js" type="text/javascript"></script>';
		showsetting(lang('plugin/yiqixueba','cardkaishi'),'cardcat_info[cardkaishi]',$cardcat_info['cardkaishi'],'calendar','',0,lang('plugin/yiqixueba','cardkaishi_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardyouxiaoqi'),'cardcat_info[cardyouxiaoqi]',$cardcat_info['cardyouxiaoqi'],'calendar','',0,lang('plugin/yiqixueba','cardyouxiaoqi_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','carddzyouxiaoqi'),'cardcat_info[carddzyouxiaoqi]',$cardcat_info['carddzyouxiaoqi'],'text','',0,lang('plugin/yiqixueba','carddzyouxiaoqi_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardqingling'),'cardcat_info[cardqingling]',$cardcat_info['cardqingling'],'calendar','',0,lang('plugin/yiqixueba','cardqingling_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','card_status'),'cardcat_info[status]',$cardcat_info['status'],'radio','',0,lang('plugin/yiqixueba','card_status_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','card_islogin'),'card_islogin','','radio','',0,lang('plugin/yiqixueba','card_islogin_comment'),'','',true);
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/kindeditor.js" type="text/javascript"></script>';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/themes/default/default.css" />';
		echo '<link rel="stylesheet" href="source/plugin/yiqixueba/template/kindeditor/plugins/code/prettify.css" />';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/lang/zh_CN.js" type="text/javascript"></script>';
		echo '<script src="source/plugin/yiqixueba/template/kindeditor/prettify.js" type="text/javascript"></script>';
		echo '<tr class="noborder" ><td colspan="2" class="td27" s="1">'.lang('plugin/yiqixueba','cardcatdescription').':</td></tr>';
		echo '<tr class="noborder" ><td colspan="2" ><textarea name="cardcatdescription" style="width:700px;height:200px;visibility:hidden;">'.$cardcat_info['cardcatdescription'].'</textarea></td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOF
<script>
	KindEditor.ready(function(K) {
		var editor1 = K.create('textarea[name="cardcatdescription"]', {
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
		if(!htmlspecialchars(trim($_GET['cardcat_info']['cardcatname']))) {
			cpmsg(lang('plugin/yiqixueba','cardcatname_nonull'));
		}
		$cardcatico = addslashes($_POST['cardcatico']);
		if($_FILES['cardcatico']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['cardcatico'], 'common') && $upload->save()) {
				$cardcatico = $upload->attach['attachment'];
			}
		}
		if($_POST['delete1'] && addslashes($_POST['cardcatico'])) {
			$valueparse = parse_url(addslashes($_POST['cardcatico']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['cardcatico']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'temp/'.addslashes($_POST['cardcatico']));
			}
			$cardcatico = '';
		}
		$datas = $_GET['cardcat_info'];
		$datas['cardcatico'] = $cardcatico;
		$datas['cardcatdescription'] = stripslashes($_POST['cardcatdescription']);
		foreach ( $datas as $k=>$v) {
			$data[$k] = htmlspecialchars(trim($v));
			if(!DB::result_first("describe ".DB::table('yiqixueba_yikatong_cardcat')." ".$k)) {
				$sql = "alter table ".DB::table('yiqixueba_yikatong_cardcat')." add `".$k."` varchar(255) not Null;";
				runquery($sql);
			}
		}
		$data['cardkaishi'] = strtotime(trim($data['cardkaishi']));
		$data['cardyouxiaoqi'] = strtotime(trim($data['cardyouxiaoqi']));
		$data['cardqingling'] = strtotime(trim($data['cardqingling']));
		$data['cardqingling'] = strtotime(trim($data['cardqingling']));
		if($cardcatid) {
			DB::update('yiqixueba_yikatong_cardcat',$data,array('cardcatid'=>$cardcatid));
		}else{
			DB::insert('yiqixueba_yikatong_cardcat',$data);
		}
		cpmsg(lang('plugin/yiqixueba', 'cardcat_edit_succeed'), 'action='.$this_page.'&subop=cardcatlist', 'succeed');
	}
}elseif($subop == 'makecard') {
	if (!$cardcatid){
		cpmsg(lang('plugin/yiqixueba','select_makecard'));
	}
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','makecard_edit_tips'));
		showformheader($this_page.'&subop=makecard','enctype');
		showtableheader(lang('plugin/yiqixueba','makecard_edit'));
		$cardcatid ? showhiddenfields($hiddenfields = array('cardcatid'=>$cardcatid)) : '';
		showsetting(lang('plugin/yiqixueba','card_pre'),'card_pre','','text','',0,lang('plugin/yiqixueba','card_pre_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','card_start'),'card_start','','text','',0,lang('plugin/yiqixueba','card_start_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','card_len'),'card_len','8','text','',0,lang('plugin/yiqixueba','card_len_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardpass_len'),'cardpass_len','6','text','',0,lang('plugin/yiqixueba','cardpass_len_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','cardpass_type'),array('cardpass_type',array(
			array('suiji',lang('plugin/yiqixueba','suiji')),
			array('houwei',lang('plugin/yiqixueba','houwei')),
			)),'','select','',0,lang('plugin/yiqixueba','cardpass_type_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','card_num'),'card_num','100','text','',0,lang('plugin/yiqixueba','card_num_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		$cardlength = intval($_POST['card_len']);
		$passlength = intval($_POST['cardpass_len']);
		$startno = intval($_POST['card_start']);
		$addnum = intval($_POST['card_num']);
		$cardtype = intval($_POST['card_islogin']);
		$cardpici = intval(DB::result_first("SELECT cardpici FROM ".DB::table('yiqixueba_yikatong_card')." order by cardpici desc"))+1;
		$noprefix = dhtmlspecialchars(trim($_POST['card_pre']));
		$cardpasstype = dhtmlspecialchars(trim($_POST['cardpass_type']));
		$adds = array();
		$nolen = $cardlength - strlen($noprefix);
		for ($insertnum = 0, $repeatnum = 0, $i = 0;$i < $addnum;$i++) {
			$format = "%0{$nolen}d";
			$no = $noprefix.sprintf($format, $i + $startno);
			if ($cardpasstype == 'suiji'){
				$adds[$i] = random($passlength);
			}else{
				$adds[$i] = substr($no,-$passlength);
			}
			$data = array();
			$data['cardno'] = $no;
			$data['cardpass'] = $adds[$i];
			$data['cardtype'] = $cardtype;
			$data['cardpici'] = $cardpici;
			$data['cardcatid'] = $cardcatid;
			$data['maketime'] = $_G['timestamp'];
			$data['status'] = 0;
			if (DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_yikatong_card')." WHERE cardno='".$no."'")==0){
				if ($cardtype){
					if(DB::result_first("SELECT count(*) FROM ".DB::table('common_member')." WHERE username='".$no."'")==0){
						$wordpass = $adds[$i];
						$username = $no;
						$email = $no.'@123.com';
						$questionid = '';
						$answer = '';
						loaducenter();
						$uid = uc_user_register($no, $wordpass, $email, $questionid, $answer, $_G['clientip']);
						$data_member['uid'] = $uid;
						$data_member['username'] = $no;
						$data_member['password'] = $wordpass;
						$data_member['email'] = $email;
						$data_member['groupid'] = $usergroup;
						$data_member['regdate'] = $_G['timestamp'];
						$data_member['status'] = 0;
						$data_member['emailstatus'] = 0;
						$data_member['avatarstatus'] = 0;
						$data_member['videophotostatus'] = 0;
						$data_member['adminid'] = 0;
						$data_member['groupexpiry'] = 0;
						$data_member['extgroupids'] = '';
						$data_member['credits'] = 0;
						$data_member['notifysound'] = 0;
						$data_member['timeoffset'] = 9999;
						$data_member['newpm'] = 0;
						$data_member['newprompt'] = 0;
						$data_member['accessmasks'] = 0;
						$data_member['allowadmincp'] = 0;
						$data_member['onlyacceptfriendpm'] = 0;
						$data_member['conisbind'] = 0;
						DB::insert('common_member', $data_member);
						$data['uid'] = $uid;
					}
				}else{
					$data['uid'] = 0;
				}
				$insertnum++;
				DB::insert('yiqixueba_yikatong_card', $data);
			}
		}
		cpmsg($insertnum.lang('plugin/yiqixueba', 'cardcat_make_succeed'), 'action='.$this_page.'&subop=cardcatlist', 'succeed');
	}
}elseif($subop == 'cardexport') {
}elseif($subop == 'cardimport') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/yiqixueba','cardimport_edit_tips'));
		showformheader($this_page.'&subop=cardimport&cardcatid='.$cardcatid,'enctype="multipart/form-data"');
		showtableheader(lang('plugin/yiqixueba','cardimport_edit'));
		$cardcatid ? showhiddenfields($hiddenfields = array('cardcatid'=>$cardcatid)) : '';
		showsetting(lang('plugin/yiqixueba','import_file'),'import_file','','file','',0,lang('plugin/yiqixueba','import_file_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		dump($cardcat_info);
		$cardpici = intval(DB::result_first("SELECT cardpici FROM ".DB::table('yiqixueba_yikatong_card')." order by cardpici desc"))+1;
		if($_FILES['import_file']["size"]>200000){
			cpmsg('文件太大了，上传文件应少于20M');
		}else{
			$plugin_temp_dir = DISCUZ_ROOT."source/plugin/yiqixueba/data";
			$plugin_temp_file = $plugin_temp_dir."/".$_FILES["import_file"]["name"];
			if(!is_dir($plugin_temp_dir)) {
				mkdir($plugin_temp_dir, 0777);
			}
			if(file_exists( $plugin_temp_file)){
				unlink( $plugin_temp_file);
			}
			move_uploaded_file($_FILES["import_file"]["tmp_name"], $plugin_temp_file);
			require_once DISCUZ_ROOT.'source/plugin/yiqixueba/admin/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP936');
			$data->read($plugin_temp_file);
			for($i=3;$i<=$data->sheets[0]['numRows'];$i++ ){

				$daoru_data = array();
				$daoru_data['cardno'] = $data->sheets[0]['cells'][$i][1];
				$daoru_data['cardpass'] = $data->sheets[0]['cells'][$i][2];
				$daoru_data['cardtype'] = $cardcat_info['cardtype'];
				$daoru_data['cardpici'] = $cardpici;
				$daoru_data['cardcatid'] = $cardcatid;
				$daoru_data['maketime'] = $_G['timestamp'];
				$daoru_data['status'] = 0;
				dump($daoru_data);
				if (DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_yikatong_card')." WHERE cardno='".$no."'")==0){
					if ($cardtype){
						if(DB::result_first("SELECT count(*) FROM ".DB::table('common_member')." WHERE username='".$no."'")==0){
							$wordpass = $adds[$i];
							$username = $no;
							$email = $no.'@123.com';
							$questionid = '';
							$answer = '';
							//loaducenter();
							//$uid = uc_user_register($no, $wordpass, $email, $questionid, $answer, $_G['clientip']);
							$daoru_data_member['uid'] = $uid;
							$daoru_data_member['username'] = $no;
							$daoru_data_member['password'] = $wordpass;
							$daoru_data_member['email'] = $email;
							$daoru_data_member['groupid'] = $usergroup;
							$daoru_data_member['regdate'] = $_G['timestamp'];
							$daoru_data_member['status'] = 0;
							$daoru_data_member['emailstatus'] = 0;
							$daoru_data_member['avatarstatus'] = 0;
							$daoru_data_member['videophotostatus'] = 0;
							$daoru_data_member['adminid'] = 0;
							$daoru_data_member['groupexpiry'] = 0;
							$daoru_data_member['extgroupids'] = '';
							$daoru_data_member['credits'] = 0;
							$daoru_data_member['notifysound'] = 0;
							$daoru_data_member['timeoffset'] = 9999;
							$daoru_data_member['newpm'] = 0;
							$daoru_data_member['newprompt'] = 0;
							$daoru_data_member['accessmasks'] = 0;
							$daoru_data_member['allowadmincp'] = 0;
							$daoru_data_member['onlyacceptfriendpm'] = 0;
							$daoru_data_member['conisbind'] = 0;
							//DB::insert('common_member', $daoru_data_member);
							$daoru_data['uid'] = $uid;
						}
					}else{
						$daoru_data['uid'] = 0;
					}
					$insertnum++;
					//DB::insert('yiqixueba_yikatong_card', $daoru_data);
				}
				cpmsg($insertnum.lang('plugin/yiqixueba', 'cardcat_daoru_succeed'), 'action='.$this_page.'&subop=cardcatlist', 'succeed');
			}
		}
	}
}elseif($subop == 'cardlist') {
	if(!submitcheck('submit')) {
		$cardpici = intval(getgpc('cardpici'));
		$cardbind = intval(getgpc('cardbind'));
		$cardno = trim(getgpc('cardno'));
		$carduname = trim(getgpc('carduname'));
		showtips(lang('plugin/yiqixueba','card_list_tips'));
		showformheader($this_page.'&subop=cardlist');
		showtableheader();
		//每页显示条数
		$tpp = intval(getgpc('tpp')) ? intval(getgpc('tpp')) : '20';
		$select[$tpp] = $tpp ? "selected='selected'" : '';
		$tpp_options = "<option value='20' $select[20]>20</option><option value='50' $select[50]>50</option><option value='100' $select[100]>100</option>";
		//
		//////搜索内容
		echo '<tr><td>';
		//模块选择
		echo lang('plugin/yiqixueba','cardno').'&nbsp;&nbsp;<input type="text" name="cardno" value="'.$cardno.'" size="10">&nbsp;&nbsp;'.lang('plugin/yiqixueba','carduid').'&nbsp;&nbsp;<input type="text" name="carduname" value="'.$carduname.'" size="10">&nbsp;&nbsp;';
		//批次选择
		$cardpici_select = '<select name="cardpici"><option value="">'.lang('plugin/yiqixueba','all').'</option>';
		$query = DB::query("SELECT cardpici FROM ".DB::table('yiqixueba_yikatong_card')." group  by cardpici");
		while($row = DB::fetch($query)) {
			$cardpici_select .= '<option value="'.$row['cardpici'].'" '.($cardpici == $row['cardpici'] ? ' selected' : '').'>'.$row['cardpici'].'</option>';
		}
		$cardpici_select .= '</select>';
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','cardpici').'&nbsp;&nbsp;'.$cardpici_select;
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','cardbind').'&nbsp;&nbsp;<select name="cardbind"><option value="">'.lang('plugin/yiqixueba','all').'</option><option value="1" '.($cardbind == 1 ? ' selected' : '').'>'.lang('plugin/yiqixueba','nocardbind').'</option><option value="2" '.($cardbind == 2 ? ' selected' : '').'>'.lang('plugin/yiqixueba','cardbinded').'</option></select>';
		//每页显示条数
		echo "&nbsp;&nbsp;".$lang['perpage']."<select name=\"tpp\">$tpp_options</select>&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		//////搜索内容
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','card_list'));
		showsubtitle(array( lang('plugin/yiqixueba','xuhao'),lang('plugin/yiqixueba','piliang'), lang('plugin/yiqixueba','cardno'),lang('plugin/yiqixueba','cardpass'),lang('plugin/yiqixueba','cardpici'), lang('plugin/yiqixueba','carduid'),lang('plugin/yiqixueba','bindtime'),lang('plugin/yiqixueba','cardfafanguid'), ''));
		$get_text = '&tpp='.$tpp.'&cardpici='.$cardpici.'&cardbind='.$cardbind.'&cardno='.$cardno.'&carduname='.$carduname;
		//搜索条件处理
		$perpage = $tpp;
		$start = ($page - 1) * $perpage;
		$where = "";
		$where .= $cardpici ? " and cardpici=".$cardpici : "";
		$where .= $cardbind ? " and status = ".($cardbind-1) : "";
		$where .= $cardno ? " and cardno like '%".$cardno."%'" : "";
		$where .= $carduname ? " and uid =".(DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username ='".$carduname."'")) : "";
		if($where) {
			$where = " where ".substr($where,4,strlen($where)-4);
		}

		$cardcount = DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_yikatong_card').$where);
		$multi = multi($cardcount, $perpage, $page, ADMINSCRIPT."?action=".$this_page."&subop=cardlist".$get_text);

		$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_yikatong_card').$where." order by cardnoid asc limit ".$start.", ".$perpage);
		$xuhao = 1;
		while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td25"','class="td23"', 'class="td23"', 'class="td25"','class="td25"','class="td23"','class="td25"',''), array(
				$xuhao,
				"<input class=\"checkbox\" type=\"checkbox\" name=\"piliang[]\" value=\"$row[cardnoid]\">",
				$row['cardno'],
				$row['cardpass'],
				$row['cardpici'],
				$row['uid'] ? DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$row['uid']) : lang('plugin/yiqixueba','no_bind'),
				$row['bindtime'] ? dgmdate($row['bindtime'], 'dt'):lang('plugin/yiqixueba','nocardbind'),
				$row['fafanguid']? DB::result_first("SELECT username FROM ".DB::table('common_member')." WHERE uid=".$row['fafanguid']) : lang('plugin/yiqixueba','no_fafang'),
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=ajaxbind&cardno=$row[cardno]\" onclick=\"showWindow('setnav', this.href, 'get', 0);return false;\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a>",
			));
			$xuhao++;
		}
		echo '<tr><td></td><td><input name="chkall" id="chkall" type="checkbox" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'piliang\', \'chkall\')" /><label for="chkall">'.cplang('select_all').'</label></td><td colspan="7"><div class="cuspages right">'.$multi.'</div></td></tr>';
		showtablefooter();
		showtableheader('operation', 'notop');
		showtablerow('', array('class="td25"',''), array('<input class="radio" type="radio" name="optype" checked value="cardfafang">','<input type="text" name="fafanguid" value="" size="5">&nbsp;&nbsp;'.lang('plugin/yiqixueba','cardfafang')));
		$delma = random(12);
		showtablerow('', array('class="td25"',''), array('<input class="radio" type="radio" name="optype" value="carddelete">','<input type="text" name="indelma" value="" size="15">&nbsp;&nbsp;'.$delma.'<input type="hidden" name="delma" value="'.$delma.'">&nbsp;&nbsp;'.lang('plugin/yiqixueba','carddelete_tips').'<br />'.lang('plugin/yiqixueba','carddelete')));
		showsubmit('submit','submit','','','');
		showtablefooter();
		showformfooter();
	}else{
		if(is_array(getgpc('piliang'))) {
			if(getgpc('optype') == 'cardfafang') {
				if(DB::result_first("SELECT count(*) FROM ".DB::table('common_member')." WHERE uid=".intval(getgpc('fafanguid')))==0 && intval(getgpc('fafanguid'))>0 || intval(getgpc('fafanguid'))==0) {
					cpmsg(lang('plugin/yiqixueba', 'card_fafang_error'), 'action='.$this_page.'&subop=cardlist', 'error');
				}
				if(intval(getgpc('fafanguid')) && DB::result_first("SELECT count(*) FROM ".DB::table('common_member')." WHERE uid=".intval(getgpc('fafanguid')))) {
					$fafangnum = 0;
					foreach ( getgpc('piliang') as $k=>$cardid) {
						DB::update('yiqixueba_yikatong_card',array('fafanguid'=>intval(getgpc('fafanguid'))),array('cardnoid'=>$cardid,'fafanguid'=>0));
						$fafangnum++;
					}
					cpmsg($fafangnum.lang('plugin/yiqixueba', 'card_fafang_succeed'), 'action='.$this_page.'&subop=cardlist', 'succeed');
				}
			}
			if(getgpc('optype') == 'carddelete') {
				if(trim(getgpc('indelma')) == trim(getgpc('delma'))) {
					$delnum = 0;
					foreach ( getgpc('piliang') as $k=>$cardid) {
						DB::delete('yiqixueba_yikatong_card',array('cardnoid'=>$cardid,'uid'=>0));
						$delnum++;
					}
					cpmsg($delnum.lang('plugin/yiqixueba', 'card_del_succeed'), 'action='.$this_page.'&subop=cardlist', 'succeed');
				}else{
					cpmsg($insertnum.lang('plugin/yiqixueba', 'card_delma_error'), 'action='.$this_page.'&subop=cardlist', 'error');
				}
			}
		}else{
			cpmsg(lang('plugin/yiqixueba', 'card_edit_succeed'), 'action='.$this_page.'&subop=cardlist', 'succeed');
		}
	}
}elseif($subop == 'ajaxbind') {
	if(!submitcheck('submit')) {
		include template('yiqixueba:yiqixueba/default/ajax');
	}else{
	}
}
?>