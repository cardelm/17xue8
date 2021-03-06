<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('IN_SERVER', TRUE);
//服务端的程序更新,在服务端只要修改了源代码部分即时更新前端程序
function server_update(){
	require_once libfile('class/xml');
	$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
	foreach($mokuais as $k=>$v ){
	}
}//end func
////////////////////////////////////////////
//直接通过mokuai模块目录读取mokuai数据
function getmokuais(){
	$mokuai_array = array();
	$mokuai_dir = MOKUAI_DIR;
	if ($handle = opendir($mokuai_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.html" && substr($file,0,1) != ".") {
				if(is_dir($mokuai_dir.'/'.$file)){
					$mokuai_array[] = $file;
				}
			}
		}
	}
	return $mokuai_array;
}//end func

//直接通过mokuai模块目录读取mokuaiver数据
function getmokuaivers($mokuainame){
	$mokuaiver_array = array();
	$mokuai_dir = MOKUAI_DIR.'/'.$mokuainame;
	if ($handle = opendir($mokuai_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.html" && substr($file,0,1) != ".") {
				if(is_dir($mokuai_dir.'/'.$file)){
					$mokuaiver_array[] = $file;
				}
			}
		}
	}
	return $mokuaiver_array;
}//end func
//得到文件
function getfilename($filedir){
	$filenames = array();
	$pages_dir = MOKUAI_DIR.'/'.$filedir;
	if ($handle = opendir($pages_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				$filenames[] = substr($file,0,-4);
			}
		}
	}
	return $filenames;
}//end func


//更新模块xml数据
function update_mokuai($biaoshi,$version,$mukauidata){
	global $_G;
	require_once libfile('class/xml');
	$mokuais = $mokuais_temp = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/mokuai.xml"));
	if(!is_dir(MOKUAI_DIR.'/'.$biaoshi)){
		$mokuais_temp[$biaoshi]['biaoshi'] = $biaoshi;
		dmkdir(MOKUAI_DIR.'/'.$biaoshi);
	}
	$mokuaivers = getmokuaivers($biaoshi);
	if(count($mokuaivers)==1){
		$mokuais_temp[$biaoshi]['currentversion'] = $mokuaivers[0];
	}
	if(!is_dir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version)){
		$mokuais_temp[$biaoshi]['version'][$version]['biaoshi'] = $version;
		dmkdir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version);
	}
	foreach (array('Controler','Modal','View','Data') as $k => $v ){
		if(!is_dir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/'.$v)){
			dmkdir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/'.$v);
		}
	}
	foreach($mukauidata as $k2=>$v2 ){
		$mokuais_temp[$biaoshi]['version'][$version][$k2] = $v2;
	}
	if($mokuais != $mokuais_temp){
		$mokuais = $mokuais_temp;
		$mokuais = array_sort($mokuais,'displayorder','asc');
		file_put_contents (MOKUAI_DIR."/server/1.0/Data/mokuai.xml",diconv(array2xml($mokuais, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}

}
//数组排序
function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}
//递归删除目录
function deldir($dir) {
	//先删除目录下的文件：
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}

	closedir($dh);
	//删除当前文件夹：
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}//end func

//得到模块的文件名
function get_mokuaipage($mokuai,$version,$ptype){
	$mokuaiid_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$version.($ptype=='source'? '/Controler' : ($ptype=='template'? '/View' : '/Modal'));
	if ($handle = opendir($mokuaiid_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.html" && substr($file,0,1) != ".") {
				$page_array[] = substr($file,0,-4);
			}
		}
	}
	return $page_array;
}//end func
//
function getshoptemp(){
	$shoptemps = array(
				array('default', lang('plugin/yiqixueba','shoptemp_default')),
				array('qianhuang', lang('plugin/yiqixueba','shoptemp_qianhuang')),
				array('default1', lang('plugin/yiqixueba','shoptemp_default')),
			);
	return $shoptemps;
}
function getmokuailang($biaoshi,$version,$pagename){
	$page_name = str_replace('source_','',$pagename).'.php';
	$lang_text = file_get_contents(MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/Controler/'.$page_name);
	$lang_arr0 = explode("lang('plugin/yiqixueba','",$lang_text);//*yiqixueba_lang_biaoshi*
	foreach($lang_arr0 as $k=>$v ){
		$lang_arr1 = array();
		if($k>0){
			$lang_arr1 = explode("')",$v);
			if($lang_arr1[0]&& !in_array($lang_arr1[0],$lang_arr['source'])&& !stripos($v,'*yiqixueba_lang_biaoshi*')){
				$lang_arr['source'][] = $lang_arr1[0];
			}
		}
	}
	if(substr($pagename,0,9)=='template_'){
		$page_type = 'template';
		$page_name = str_replace('template_','',$pagename).'.htm';
		$lang_text = file_get_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/template/mokuai/'.$mokuaiid.'/'.$page_name);
		$lang_arr0 = explode("{lang yiqixueba:",$lang_text);
		foreach($lang_arr0 as $k=>$v ){
			$lang_arr1 = array();
			if($k>0){
				$lang_arr1 = explode("}",$v);
				if($lang_arr1[0]&& !in_array($lang_arr1[0],$lang_arr['template'])){
					$lang_arr['template'][] = $lang_arr1[0];
				}
			}
		}
	}
	return $lang_arr;
}

//升级站长组
function update_sitegroup(){
	global $_G;
	require_once libfile('class/xml');
	$systemgroups = array('ViI7m2R9QM','QiMzl6m9o6','DAxEAvi2ie');
	$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
	$sitegroups = $sitegroups_temp = xml2array(file_get_contents(MOKUAI_DIR."/sitegroups.xml"));
	foreach($systemgroups as $k=>$v ){
		unset($sitegroups_temp[$v]['installmokuai']);
		unset($sitegroups_temp[$v]['upgrademokuai']);
		unset($sitegroups_temp[$v]['nodes']);
		$sitegroups_temp[$v]['installmokuai'][] = 'main_1.0';
		$nodes = xml2array(file_get_contents(MOKUAI_DIR."/main/1.0/node.xml"));
		foreach($nodes as $k2=>$v2 ){
			$sitegroups_temp[$v]['nodes'][] = 'main_'.$k2;
		}
	}

	foreach($mokuais as $k=>$v ){
		foreach($v['version'] as $k1=>$v1 ){
			$nodes = xml2array(file_get_contents(MOKUAI_DIR."/".$k."/".$k1."/node.xml"));
			if($v1['available'] && $k != 'server'){
				$sitegroups_temp['ViI7m2R9QM']['upgrademokuai'][] = $k.'_'.$k1;
			}
			if($k != 'server'){
				$sitegroups_temp['QiMzl6m9o6']['upgrademokuai'][] = $k.'_'.$k1;
			}
			$sitegroups_temp['DAxEAvi2ie']['upgrademokuai'][] = $k.'_'.$k1;
		}
	}
	if($sitegroups != $sitegroups_temp){
		file_put_contents (MOKUAI_DIR."/sitegroups.xml",diconv(array2xml($sitegroups_temp, 1),"UTF-8", $_G['charset']."//IGNORE"));
	}
}


//
function node_init($biaoshi,$version){
	require_once libfile('class/xml');
	$nodes = xml2array(file_get_contents(MOKUAI_DIR."/".$biaoshi."/".$version."/node.xml"));
	//$langfile = MOKUAI_DIR."/".$biaoshi."/".$version."/lang.php";
	//include_once $langfile;
	//dump($langfile);
	//dump($plang);
	foreach ($nodes as $k => $v ){
		unset($nodes[$k]['table']);
		unset($nodes[$k]['lang']);
		unset($nodes[$k]['pages']);
		unset($nodes[$k]['template']);
		list($nt,$nn) = explode('_',$k);
		$nodefile = MOKUAI_DIR."/".$biaoshi."/".$version."/Controler/".$k.'.php';
		$node_text = file_get_contents($nodefile);
		if($nt && $nn){
			$ta0 = explode('C::t(GM(\'',$node_text);
			foreach ($ta0  as $k1 => $v1 ){
				if($k1!=0){
					$ta1[$k1] = explode('\'))->',$v1);
					if($ta1[$k1][0]){
						$nodes[$k]['table'][$ta1[$k1][0]] = $ta1[$k1][0];
					}

				}
			}
			$lsa0 = explode('lang(\'plugin/yiqixueba\',\'',$node_text);
			foreach ( $lsa0 as $k1 => $v1 ){
				if($k1!=0){
					$lsa1[$k1] = explode('\')',$v1);
					if($lsa1[$k1][0]){
						$nodes[$k]['lang']['scriptlang'][$lsa1[$k1][0]] = lang('plugin/yiqixueba',$lsa1[$k1][0]);
					}
				}
			}
			$pa0 = explode('require_once GC(\'',$node_text);
			foreach ( $pa0 as $k1 => $v1 ){
				if($k1!=0){
					$pa1[$k1] = explode('\')',$v1);
					if($pa1[$k1][0]){
						$nodes[$k]['pages'][$pa1[$k1][0]] = $pa1[$k1][0];
					}
				}
			}
			$ta0 = explode('include template(GV(\'',$node_text);
			foreach ( $ta0 as $k1 => $v1 ){
				if($k1!=0){
					$ta1[$k1] = explode('\'))',$v1);
					if($ta1[$k1][0]){
						$nodes[$k]['template'][$ta1[$k1][0]] = $ta1[$k1][0];
					}
				}
			}
			foreach ( $nodes[$k]['template'] as $k2 => $v2 ){
				list($m,$t,$n) = explode('_',$k2);
				$tempfile = MOKUAI_DIR."/".$m."/".$version."/View/".$t.'_'.$n.'.htm';
				$temp_text = file_get_contents($tempfile);
				$lta0 = explode('{lang yiqixueba:',$temp_text);
				foreach ( $lta0 as $k1 => $v1 ){
					if($k1!=0){
						$lta1[$k1] = explode('}',$v1);
						if($lta1[$k1][0]){
							$nodes[$k]['lang']['templatelang'][$lta1[$k1][0]] = $lta1[$k1][0];
						}
					}
				}

			}
		}
	}
	dump($nodes);
	return $nodes;
}//end func


//////////以后再用
//
function writenode($nodedata){
	$nodedata = _nodedata_init($nodedata);
	if($nodedata['nodetype'] == 'admincp'){
		_writeadmincpnode($nodedata);
	}elseif($nodedata['nodetype'] == 'yiqixueba'){
		_writeyiqixuebanode($nodedata);
	}
	if($nodedata['nodetype'] == 'member'){
		_writemembernode($nodedata);
	}

}//end func




//
function _pagedata_init($pagedata){

	$pagetypes = array('admincp','yiqixueba','member','ajax','api','hook','plugin');
	$datetypes = array('table','xml','config');
	$optiontypes = array('setting','list','edit');
	$listtypes = array('noadd','nodel','noorder','nopage','nosearch');

	$pagedata['mokuai'] = $pagedata['mokuai'] ? $pagedata['mokuai'] : 'server';
	$pagedata['version'] = $pagedata['version'] ? $pagedata['version'] : '1.0';

	$pagedata['pagename'] = $pagedata['pagename'] ? $pagedata['pagename'] : 'example';

	$pagedata['pagetype'] = $pagedata['pagetype'] ? $pagedata['pagetype'] : $pagetypes[0];

	$pagedata['controlerdir'] = $pagedata['controlerdir'] ? $pagedata['controlerdir'] : MOKUAI_DIR.'/'.$pagedata['mokuai'].'/'.$pagedata['version'].'/Controler/';
	$pagedata['modaldir'] = $pagedata['modal'] ? $pagedata['modal'] : MOKUAI_DIR.'/'.$pagedata['mokuai'].'/'.$pagedata['version'].'/Modal/';
	$pagedata['viewdir'] = $pagedata['view'] ? $pagedata['view'] : MOKUAI_DIR.'/'.$pagedata['mokuai'].'/'.$pagedata['version'].'/View/';

	$pagedata['controlerfile'] = $pagedata['controlerfile'] ? $pagedata['controlerfile'] : $pagedata['pagename'];

	$pagedata['nodes'] = $pagedata['nodes'] ? $pagedata['nodes'] : array($pagedata['pagename'].'list',$pagedata['pagename'].'edit');

	if(is_array($pagedata['nodes'])){
		foreach ($pagedata['nodes']  as $k => $v ){
			if(!$v && !is_array($v)){
				$pagedata['nodedata'][$v] = _pagedata_init($v);
			}
		}
	}


	$nodedata['optiontype'] = $nodedata['optiontype'] ? $nodedata['optiontype'] : $optiontypes[0];
	if(!is_array($nodedata['optiontype']) && $nodedata['optiontype'] == 'list' || is_array($nodedata['optiontype']) && in_array('list',$nodedata['optiontype'])){
		foreach ($listtypes  as $k => $v ){
			$nodedata['listtype'][$v] = $nodedata['listtype'][$v] ? $nodedata['listtype'][$v] : false;
		}
	}

	$nodedata['modalfile'] = $nodedata['modalfile'] ? $nodedata['modalfile'] : $nodedata['nodename'];
	$nodedata['viewfile'] = $nodedata['viewfile'] ? $nodedata['viewfile'] : $nodedata['nodename'];


	$nodedata['datetype'] = $nodedata['datetype'] ? $nodedata['datetype'] : $datetypes[0];

	if(empty($nodedata['fields']) || !isset($nodedata['fields']) || !$nodedata['fields'] || !is_array($nodedata['fields'])){
		$nodedata['fields'] = array(
			array(
				'name' => $nodedata['nodename'].'id',
				'title' => 'exampleid',
				'type' => 'int',
				'length' => '8',
				'xiaoshu' => '0',
				'notnull' => true,
				'unsigned' => true,
				'primarykey' => true,
				'orderdisplay' => 1,
				'defaultvalue' => 'auto_increment',
			),
			array(
				'name' => $nodedata['nodename'].'name',
				'title' => 'examplename',
				'type' => 'char',
				'length' => '20',
				'xiaoshu' => '0',
				'notnull' => true,
				'primarykey' => false,
				'orderdisplay' => 2,
				'defaultvalue' => '',
			),
		);
	}

	return $nodedata;
}//end func
//
function _writeadmincpnode($nodedata){
	dump($nodedata);
	return $nodedata;
}//end func
//
function _writeyiqixuebanode($nodedata){
	dump($nodedata);
	return $nodedata;
}//end func
//
function _writemembernode($nodedata){
	dump($nodedata);
	return $nodedata;
}//end func
?>