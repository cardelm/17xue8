<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



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

//更新模块xml数据
function update_mokuai($biaoshi,$version,$mukauidata){
	global $_G;
	require_once libfile('class/xml');
	$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
	if(!is_dir(MOKUAI_DIR.'/'.$biaoshi)){
		$mokuais[$biaoshi]['biaoshi'] = $biaoshi;
		dmkdir(MOKUAI_DIR.'/'.$biaoshi);
	}
	$mokuaivers = getmokuaivers($biaoshi);
	if(count($mokuaivers)==1){
		$mokuais[$biaoshi]['currentversion'] = $mokuaivers[0];
	}
	if(!is_dir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version)){
		$mokuais[$biaoshi]['version'][$version]['biaoshi'] = $version;
		dmkdir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version);
	}
	foreach (array('Controler','Modal','View','Data') as $k => $v ){
		if(!is_dir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/'.$v)){
			dmkdir(MOKUAI_DIR.'/'.$biaoshi.'/'.$version.'/'.$v);
		}
	}
	foreach($mukauidata as $k2=>$v2 ){
		$mokuais[$biaoshi]['version'][$version][$k2] = $v2;
	}
	file_put_contents (MOKUAI_DIR."/mokuai.xml",diconv(array2xml($mokuais, 1),"UTF-8", $_G['charset']."//IGNORE"));
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
//
function getmod($mokuai,$ver){
	$modfile = array();
	$pages_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$ver.'/Controler';
	if ($handle = opendir($pages_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				$modfile[] = substr($file,0,-4);
			}
		}
	}
	return $modfile;
}//end func

//
function get_mokuaipage($mokuai,$version,$ptype){
	$mokuaiid_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$mokuai.'/'.$version.($ptype=='source'? '/Controler' : ($ptype=='template'? '/View' : '/Modal'));
	if ($handle = opendir($mokuaiid_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "index.html" && substr($file,0,1) != ".") {
				$page_array[] = substr($file,0,-4);
			}
		}
	}
	return $page_array;
}//end func
function getmokuailang($biaoshi,$version,$pagename){
	if(substr($pagename,0,7)=='source_'){
		$page_type = 'source';
		$page_name = str_replace('source_','',$pagename).'.php';
		$lang_text = file_get_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$biaoshi.'/'.$version.'/Controler/'.$page_name);
		$lang_arr0 = explode("lang('plugin/yiqixueba','",$lang_text);//*yiqixueba_lang_biaoshi*
		foreach($lang_arr0 as $k=>$v ){
			//dump(stripos($v,'*yiqixueba_lang_biaoshi*'));
			$lang_arr1 = array();
			if($k>0){
				$lang_arr1 = explode("')",$v);
				if($lang_arr1[0]&& !in_array($lang_arr1[0],$lang_arr['source'])&& !stripos($v,'*yiqixueba_lang_biaoshi*')){
					$lang_arr['source'][] = $lang_arr1[0];
				}
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


?>