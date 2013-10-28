<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$this_page = substr($_SERVER['QUERY_STRING'],7,strlen($_SERVER['QUERY_STRING'])-7);
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('shopsortlist','shopsortedit');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$shopsortid = getgpc('shopsortid');
$shopsort_info = C::t(GM('shop_shopsort'))->fetch($shopsortid);

$sortupid = intval(getgpc('sortupid'));

if($subop == 'shopsortlist') {
	if(!submitcheck('submit')) {

		showtips(lang('plugin/yiqixueba','shopsort_list_tips'));
		showformheader($this_page.'&subop=shopsortlist');
		showtableheader();

		//////搜索内容
		echo '<tr><td>';
		//分类选择
		$sortupid_select = '<select name="sortupid"><option value="0">'.lang('plugin/yiqixueba','shopsort_top').'</option>';
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_shop_shopsort')." where sortupid = ".$sortupid." order by concat(upids,'-',shopsortid) asc");
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_shop_shopsort')." order by concat(upids,'-',shopsortid) asc");
		while($row = DB::fetch($query)) {
			$sortupid_select .= '<option value="'.$row['shopsortid'].'" '.($sortupid == $row['shopsortid'] ? ' selected' :'').'>'.str_repeat("--",$row['sortlevel']-1).$row['sorttitle'].'</option>';
		}
		$sortupid_select .= '</select>';
		echo '&nbsp;&nbsp;'.lang('plugin/yiqixueba','shopsort_select').'&nbsp;&nbsp;'.$sortupid_select;
		echo "&nbsp;&nbsp;<input class=\"btn\" type=\"submit\" value=\"$lang[search]\" /></td></tr>";
		//////搜索内容
		showtablefooter();
		showtableheader(lang('plugin/yiqixueba','shopsort_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','shopsortname'),lang('plugin/yiqixueba','shopsorttitle'), lang('plugin/yiqixueba','displayorder'), ''));

		$shopsorts = C::t(GM('shop_shopsort'))->range();
		foreach($shopsorts as $k=>$row ){
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[shopsortid]\">",
				str_repeat("--",$row['sortlevel']-1).$row['sortname'],
				str_repeat("--",$row['sortlevel']-1).$row['sorttitle'],
				'<input type="text" class="txt" name="displayordernew['.$row['shopsortid'].']" value="'.$row['displayorder'].'" size="2" />',
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=shopsortedit&shopsortid=$row[shopsortid]\" class=\"act\">".lang('plugin/yiqixueba','edit')."</a>",
		));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subop=shopsortedit&upmokuai='.$upmokuai.'&sortupid='.$sortupid.'" class="addtr">'.lang('plugin/yiqixueba','add_shopsort').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
		if($idg = $_GET['delete']) {
			$idg = dintval($idg, is_array($idg) ? true : false);
			if($idg) {
				DB::delete('yiqixueba_shop_shopsort', DB::field('shopsortid', $idg));
			}
		}
		$displayordernew = $_GET['displayordernew'];
		if(is_array($displayordernew)) {
			foreach ( $displayordernew as $k=>$v) {
				DB::update('yiqixueba_shop_shopsort',array('displayorder'=>intval($v)),array('shopsortid'=>$k));
			}
		}
		cpmsg(lang('plugin/yiqixueba_server', 'sort_edit_succeed'), 'action='.$this_page.'&subop=shopsortlist&upmokuai='.$upmokuai.'&sortupid='.$sortupid, 'succeed');
	}
}elseif($subop == 'shopsortedit') {
	if(!submitcheck('submit')) {
		$upmokuai = $shopsort_info['upmokuai'] ? $shopsort_info['upmokuai'] : $upmokuai;
		$sortupid = $shopsort_info['sortupid'] ? $shopsort_info['sortupid'] : $sortupid;
		$sortupid_select = '<select name="sortupid"><option value="0">'.lang('plugin/yiqixueba','shopsort_top').'</option>';
		//$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_shop_shopsort')." order by concat(upids,'-',shopsortid) asc");
		while($row = DB::fetch($query)) {
			$sortupid_select .= '<option value="'.$row['shopsortid'].'" '.($shopsort_info['sortupid'] == $row['shopsortid'] ? ' selected' :'').'>'.str_repeat("--",$row['sortlevel']-1).$row['sorttitle'].'</option>';
		}
		$sortupid_select .= '</select>';


		showtips(lang('plugin/yiqixueba','shopsort_edit_tips'));
		showformheader($this_page.'&subop=shopsortedit','enctype');
		showtableheader(lang('plugin/yiqixueba','shopsort_edit'));
		$shopsortid ? showhiddenfields(array('shopsortid'=>$shopsortid)) : '';
		$upmokuai ? showhiddenfields(array('upmokuai'=>$upmokuai)) : '';
		$sortupid ? showhiddenfields(array('sortupid'=>$sortupid)) : '';
		showsetting(lang('plugin/yiqixueba','sortupid'),'','',$sortupid_select,'',0,lang('plugin/yiqixueba','sortupid_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shopsortname'),'shopsortname',$shopsort_info['sortname'],'text','',0,lang('plugin/yiqixueba','shopsortname_comment'),'','',true);
		showsetting(lang('plugin/yiqixueba','shopsorttitle'),'shopsorttitle',$shopsort_info['sorttitle'],'text','',0,lang('plugin/yiqixueba','shopsorttitle_comment'),'','',true);
		//showsetting(lang('plugin/yiqixueba','displayorder'),'displayorder',$shopsort_info['displayorder'],'text','',0,lang('plugin/yiqixueba','displayorder_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		if(!htmlspecialchars(trim($_GET['shopsortname']))) {
			cpmsg(lang('plugin/yiqixueba','shopsortname_nonull'));
		}
		$data = array();
		$data['sortname'] = htmlspecialchars(trim($_GET['shopsortname']));
		$data['sorttitle'] = htmlspecialchars(trim($_GET['shopsorttitle']));
		$data['sortupid'] = intval($_GET['sortupid']);

		$data['upids'] = intval($_GET['sortupid']) ? trim(DB::result_first("SELECT upids FROM ".DB::table('yiqixueba_shop_shopsort')." WHERE shopsortid=".intval($_GET['sortupid']))).'-'.intval($_GET['sortupid']) : intval($_GET['sortupid']);


		$data['sortlevel'] = $data['sortupid'] ==0 ? 1 : (intval(DB::result_first("SELECT sortlevel FROM ".DB::table('yiqixueba_shop_shopsort')." WHERE shopsortid=".$data['sortupid']))+1);
		$data['displayorder'] = htmlspecialchars(trim($_GET['displayorder']));

		if($shopsortid) {
			C::t(GM('shop_shopsort'))->update($shopsortid,$data);
		}else{
			C::t(GM('shop_shopsort'))->insert($data);
		}
		echo '<style>.floattopempty { height: 30px !important; height: auto; } </style>';
		cpmsg(lang('plugin/yiqixueba', 'shopsort_edit_succeed'), 'action='.$this_page.'&subop=shopsortlist', 'succeed');
	}
}
