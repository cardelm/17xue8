<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//////////////////////////////////////////////////////////////////
//程序调试的时候，自动更新github程序
$this_dir = dirname(__FILE__);
if ($this_dir == DISCUZ_ROOT.'source\plugin\yiqixueba'){
	yiqixueba_init();
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
						//file_put_contents ($out_path."/".$file,diconv(file_get_contents($path."/".$file),"UTF-8", "GBK//IGNORE"));
					}
				}
			}
		}
	}
}

//
function yiqixueba_init(){
	global $_G;
	if(!C::t('common_setting')->skey_exists('yiqixueba_siteurlkey')){
		$salt = random(6);
		$yiqixueba_settings = array(
			'yiqixueba_salt' => $salt,
			'yiqixueba_siteurlkey' => md5($_G['siteurl'].$salt),
			'yiqixueba_mainver' => '1.0',
			//'yiqixueba_basepage' => '1.0',
		);
		C::t('common_setting')->update_batch($yiqixueba_settings);
	}
	$siteurlkey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	$ver = C::t('common_setting')->fetch('yiqixueba_mainver');
	write_source('main',$ver);
	write_table('main',$ver);
	write_template('main',$ver);
}//end func

//
function write_source($mokuai,$ver){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	$source_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/source/'.$mokuai.'/'.$ver.'/Controler';
	$target_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/pages';
	if ($handle = opendir($source_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				list($filename) = explode(".",$file);
				if (filemtime($target_dir.'/'.md5($sitekey.$mokuai.'_'.$filename))<filemtime($source_dir.'/'.$file)){
					file_put_contents($target_dir.'/'.md5($sitekey.$mokuai.'_'.$filename),convert_uuencode(file_get_contents($source_dir.'/'.$file)));
				}
			}
		}
	}
}//end func
//
function write_table($mokuai,$ver){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	$source_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/source/'.$mokuai.'/'.$ver.'/Modal';
	$target_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/table';
	if ($handle = opendir($source_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				list($filename) = explode(".",$file);
				if (filemtime($target_dir.'/table_'.md5($sitekey.$mokuai.'_'.$filename).'.php')<filemtime($source_dir.'/'.$file)){
					file_put_contents($target_dir.'/table_'.md5($sitekey.$mokuai.'_'.$filename).'.php',file_get_contents($source_dir.'/'.$file));
				}
			}
		}
	}
}//end func
//
function write_template($mokuai,$ver){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
	$source_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/source/'.$mokuai.'/'.$ver.'/View';
	$target_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/template';
	if ($handle = opendir($source_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				list($filename) = explode(".",$file);
				if (filemtime($target_dir.'/'.md5($sitekey.$mokuai.'_'.$filename).'.htm')<filemtime($source_dir.'/'.$file)){
					file_put_contents($target_dir.'/'.md5($sitekey.$mokuai.'_'.$filename).'.htm',file_get_contents($source_dir.'/'.$file));
				}
			}
		}
	}
}//end func
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
make_page();

//
function require_cache($filename){
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
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
	$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
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
function make_mokuai(){
}//end func


function dump($var, $echo=true,$label=null, $strict=true){
	$label = ($label===null) ? '' : rtrim($label) . ' ';
	if(!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
		} else {
			$output = $label . " : " . print_r($var, true);
		}
	}else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if(!extension_loaded('xdebug')) {
			$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
			$output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
		return $output;
}
?>