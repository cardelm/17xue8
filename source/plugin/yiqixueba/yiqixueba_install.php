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
///////////////////////////////////////////////////////////////////////



if(!C::t('common_setting')->skey_exists('yiqixueba_siteurlkey')){
	$salt = random(6);
	$yiqixueba_settings = array(
		'yiqixueba_salt' => $salt,
		'yiqixueba_siteurlkey' => md5($_G['siteurl'].$salt),
		'yiqixueba_mainver' => '1.0',
	);
	C::t('common_setting')->update_batch($yiqixueba_settings);
}

yiqixueba_init();
function yiqixueba_init(){
	global $_G;
	if(!C::t('common_setting')->skey_exists('yiqixueba_siteurlkey')){
		$salt = random(6);
		$yiqixueba_settings = array(
			'yiqixueba_salt' => $salt,
			'yiqixueba_siteurlkey' => md5($_G['siteurl'].$salt),
			'yiqixueba_mainver' => '1.0',
			'yiqixueba_basepage' => md5(md5($_G['siteurl'].$salt)),
		);
		C::t('common_setting')->update_batch($yiqixueba_settings);
	}
	file_put_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage'),file_get_contents('C:/GitHub/17xue8/source/plugin/yiqixueba/mokuai/main/1.0/Controler/function.php'));

}


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