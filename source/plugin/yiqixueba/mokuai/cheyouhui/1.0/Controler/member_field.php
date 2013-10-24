<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$this_page = 'plugin.php?'.$_SERVER['QUERY_STRING'];
stripos($this_page,'subop=') ? $this_page = substr($this_page,0,stripos($this_page,'subop=')-1) : $this_page;

$subops = array('fieldlist','fieldedit','fielddel');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$fieldtype = getgpc('fieldtype');
$fieldtype = in_array($fieldtype,array('jsz','chl')) ? $fieldtype : 'jsz';

$fieldclass_s = array('num','text','textarea','select','mselect','radio','checkbox','image','ditu','time','date');
foreach($fieldclass_s as $k=>$v ){
	$fieldclass_array[$v] = lang('plugin/yiqixueba','fieldclass_'.$v);
}

$sjznum = C::t(GM('cheyouhui_field')) -> count_by_fieldtype('jsz');
$chlnum = C::t(GM('cheyouhui_field')) -> count_by_fieldtype('chl');
$zongnum = $sjznum + $chlnum;
$zongnum = $zongnum ? $zongnum :'0';
$sjznum = $sjznum ? $sjznum :'0';
$chlnum = $chlnum ? $chlnum :'0';

if($subop == 'fieldlist') {
	$page = empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page=1;
	$perpage = 20;
	$start = ($page-1)*$perpage;

	$fields = C::t(GM('cheyouhui_field'))->fetch_all_by_search($fieldtype,$start,$perpage);

	$theurl = $this_page.'&fieldtype='.$fieldtype;
	$multi = '';
	if($fieldtype == 'jsz'){
		$count = $sjznum;
	}
	if($fieldtype == 'chl'){
		$count = $chlnum;
	}
	if($count) {
		$multi = multi($count, $perpage, $page, $theurl);
	}
}elseif($subop == 'fieldedit') {
	if(!submitcheck('submit')) {
		$fieldname = dhtmlspecialchars(trim($_GET['fieldname']));
		if($fieldname){
			list($fieldtype,$name) = explode("_",$fieldname);
			$field_info = C::t(GM('cheyouhui_field'))->fetch_all_by_fieldname($fieldname);
		}
	}else{
		$fieldname = dhtmlspecialchars(trim($_GET['fieldname']));
		$fieldtitle = dhtmlspecialchars(trim($_GET['fieldtitle']));
		$fieldtype = addslashes($_GET['fieldtype']);
		$fieldtips = dhtmlspecialchars(trim($_GET['fieldtips']));
		$fieldclass = dhtmlspecialchars(trim($_GET['fieldclass']));
		$fieldrequired = intval($_GET['isrequired']);
		$fielddisplayorder = intval($_GET['displayorder']);
		$fieldparameter = serialize($_GET['parameter']);
		if(!$fieldname){
			showmessage(lang('plugin/yiqixueba','nofieldname'));
		}
		if(!$fieldtitle){
			showmessage(lang('plugin/yiqixueba','nofieldtitle'));
		}
		$data['fieldname'] = $fieldtype.'_'.$fieldname;
		$data['fieldtitle'] = $fieldtitle;
		$data['fieldtips'] = $fieldtips;
		$data['fieldclass'] = $fieldclass;
		$data['isrequired'] = $fieldrequired;
		$data['displayorder'] = $fielddisplayorder;
		$data['fieldparameter'] = $fieldparameter;
		C::t(GM('cheyouhui_field'))->insert($data);
		showmessage(lang('plugin/yiqixueba','addfield_success'), $this_page );
	}
}elseif($subop == 'fielddel') {
	C::t(GM('cheyouhui_field'))->delete(getgpc('fieldname'));
	showmessage(lang('plugin/yiqixueba','delfield_success'), $this_page );
}

$subtpl = GV('cheyouhui_field');


?>