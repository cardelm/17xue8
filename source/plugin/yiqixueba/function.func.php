<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//////////////////////////////////////////////////////////////////
//程序调试的时候，自动更新github程序
$this_dir = dirname(__FILE__);
if ($this_dir == DISCUZ_ROOT.'source\plugin\yiqixueba'){
	check_github_update();
}
//程序调试的时候，自动更新github程序
function check_github_update($path=''){
	global $_G;
	$github_dir = 'C:\GitHub\17xue8';//本地的GitHub的17xue8文件夹
	clearstatcache();
	if($path=='')
		$path = $github_dir;

	$out_path = substr(DISCUZ_ROOT,0,-1).str_replace($github_dir,'',$path);//本地的wamp的17xue8文件夹

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != ".") {
				if (is_dir($path."/".$file)) {
					if (!is_dir($out_path."/".$file)){
						dmkdir($out_path."/".$file);
					}
					check_github_update($path."/".$file);
				}else{
					if (filemtime($path."/".$file)>filemtime($out_path."/".$file)){//GitHub文件修改时间大于wamp时
						file_put_contents ($out_path."/".$file,diconv(file_get_contents($path."/".$file),"UTF-8", "GBK//IGNORE"));
					}
				}
			}
		}
	}
}
///////////////////////////////////////////////////////////////////


//这里将来用于服务端函数集
//生成加密页目录
$page_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/pages';
if(!is_dir($page_dir)){
	dmkdir($page_dir);
}
//生成缓存目录
$runtime_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime';
if(!is_dir($runtime_dir)){
	dmkdir($runtime_dir);
}


//
function require_cache($filename){
	$sitekey = getstiekey();
	$pages_filename = DISCUZ_ROOT.'source\plugin\yiqixueba\pages/'.md5($sitekey.$filename);
	if(file_exists($pages_filename)){
		$runtime_filename = DISCUZ_ROOT.'source\plugin\yiqixueba\runtime/~'.md5($sitekey.$filename.filemtime($pages_filename)).'.php';
		if(!file_exists($runtime_filename)){
			file_put_contents($runtime_filename,convert_uudecode(file_get_contents($pages_filename)));
		}
		require_once($runtime_filename);
	}
	
}//end func
//
function make_page(){
	$sitekey = getstiekey();
	$mokuai_array = array('main','server');
	$mokuai_dir = DISCUZ_ROOT.'source\plugin\yiqixueba\source';
	foreach ( $mokuai_array as $k => $v ){
		if ($handle = opendir($mokuai_dir.'/'.($k+1))) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && substr($file,0,1) != "."&& $file != "index.html") {
					$old_file_name = $mokuai_dir.'/'.($k+1).'/'.$file;
					$new_file_name = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.md5($sitekey.$v.'_'.substr($file,0,-4));
					if (filemtime($new_file_name)<filemtime($old_file_name)){
						$runtime_filename = DISCUZ_ROOT.'source\plugin\yiqixueba\runtime/~'.md5($sitekey.$v.'_'.substr($file,0,-4).filemtime($new_file_name)).'.php';
						unlink($runtime_filename);
						file_put_contents($new_file_name,convert_uuencode(file_get_contents($old_file_name)));
					}
				}
			}
		}
		
	}
}//end func

//
function getstiekey(){
	global $_G;
	$salt = 'y4spU1';
	$sitekey = md5($_G['siteurl'].$salt);
	return $sitekey;
}//end func

//
function make_mokuai(){
}//end func
?>