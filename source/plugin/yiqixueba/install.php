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

if(!C::t('common_setting')->skey_exists('yiqixueba_basepage')){
	C::t('common_setting')->update('yiqixueba_basepage',md5('ceshi'));
}
if(!is_dir(DISCUZ_ROOT.'source/plugin/yiqixueba/runtime')){
	dmkdir(DISCUZ_ROOT.'source/plugin/yiqixueba/runtime');
}
if(!is_dir(DISCUZ_ROOT.'source/plugin/yiqixueba/pages')){
	dmkdir(DISCUZ_ROOT.'source/plugin/yiqixueba/pages');
}
if(!is_dir(DISCUZ_ROOT.'source/plugin/yiqixueba/table')){
	dmkdir(DISCUZ_ROOT.'source/plugin/yiqixueba/table');
}
if(!is_dir(DISCUZ_ROOT.'source/plugin/yiqixueba/template')){
	dmkdir(DISCUZ_ROOT.'source/plugin/yiqixueba/template');
}
file_put_contents(DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.C::t('common_setting')->fetch('yiqixueba_basepage'),file_get_contents('C:/GitHub/17xue8/source/plugin/yiqixueba/mokuai/main/1.0/Controler/function.php'));

$mokuai = 'main';
$ver = '1.0';
$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');
updatemokuai();
unset($mokuai);
unset($ver);
//
function updatemokuai($path=''){
	global $mokuai,$ver,$sitekey;
	$mokuai_dir = DISCUZ_ROOT.'source/plugin/yiqixueba/mokuai/'.$mokuai.'/'.$ver;//模块目录
	clearstatcache();
	if($path=='')
		$path = $mokuai_dir;

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				if (is_dir($path."/".$file)) {
					updatemokuai($path."/".$file);
				}else{
					$new_filename = str_replace("/","_",substr(str_replace($mokuai_dir,"",$path."/".$file),1));
					list($type) = explode("_",$new_filename);
					$new_file = in_array($type,array('Controler','Modal','View')) ? str_replace($type,$mokuai,$new_filename) : $new_filename;
					$new_file = substr($new_file,0,-4);
					if($type == 'Controler'){
						$page_file = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.md5($sitekey.$new_file);
						if (filemtime($path."/".$file)>filemtime($page_file) || !file_exists($page_file)){
							@unlink(DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.md5($sitekey.md5($sitekey.$new_file).filemtime($page_file)));
							file_put_contents($page_file,convert_uuencode(file_get_contents($path."/".$file)));
						}
						$runtime_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.md5($sitekey.md5($sitekey.$new_file).filemtime($page_file));
						if(filemtime($page_file)>filemtime($runtime_file) || !file_exists($runtime_file)){
							file_put_contents($runtime_file,convert_uudecode(file_get_contents($page_file)));
						}
					}elseif($type == 'Modal'){
						
						$table_file = DISCUZ_ROOT.'source/plugin/yiqixueba/table/table_'.md5($sitekey.$new_file).'.php';
						if(filemtime($path."/".$file)>filemtime($table_file) || !file_exists($table_file)){
							file_put_contents($table_file,str_replace("class table_".substr($file,0,-4),"class table_".md5($sitekey.$new_file),file_get_contents($path."/".$file)));
						}
						
					}elseif($type == 'View'){
						
						$template_file = DISCUZ_ROOT.'source/plugin/yiqixueba/template/t'.md5($sitekey.$new_file).'.htm';
						if(filemtime($path."/".$file)>filemtime($template_file) || !file_exists($template_file)){
							file_put_contents($template_file,file_get_contents($path."/".$file));
						}
					
					}else{
						$new_file = $new_filename;
					}
				}
			}
		}
	}
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