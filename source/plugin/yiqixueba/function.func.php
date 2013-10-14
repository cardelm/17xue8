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
?>