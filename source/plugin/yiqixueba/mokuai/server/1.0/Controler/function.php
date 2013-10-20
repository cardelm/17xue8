<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//
function getmod($type){
	$modfile = array();
	$pages_dir = 'C:/GitHub/17xue8/source/plugin/yiqixueba/mokuai/'.$mokuai.'/'.$ver.'/Controler';
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
function make_mokuai($mokuainame,$mokuaiversion){
	if($mokuainame && $mokuaiversion){
		$mokuai_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai';
		if(!is_dir($mokuai_dir.'/'.$mokuainame)){
			dmkdir($mokuai_dir.'/'.$mokuainame);
		}
		if(!is_dir($mokuai_dir.'/'.$mokuainame.'/'.$mokuaiversion)){
			dmkdir($mokuai_dir.'/'.$mokuainame.'/'.$mokuaiversion);
		}
		foreach (array('Controler','Modal','View') as $k => $v ){
			if(!is_dir($mokuai_dir.'/'.$mokuainame.'/'.$mokuaiversion.'/'.$v)){
				dmkdir($mokuai_dir.'/'.$mokuainame.'/'.$mokuaiversion.'/'.$v);
			}
		}
	}
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