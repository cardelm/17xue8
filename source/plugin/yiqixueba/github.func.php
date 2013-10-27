<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
define('MOKUAI_DIR', 'C:\GitHub\17xue8/source/plugin/yiqixueba/mokuai');
//////////////////////////////////////////////////////////////////
//程序调试的时候，自动更新github程序
$this_dir = dirname(__FILE__);
if ($this_dir == DISCUZ_ROOT.'source\plugin\yiqixueba'){
	check_github_update();
}
//程序调试的时候，自动更新github程序
function check_github_update($path=''){
	global $_G;
	//dump('1');
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

$github_ver = '1.0';

if(!C::t('common_setting')->skey_exists('yiqixueba_siteurlkey')){
	$salt = random(6);
	$yiqixueba_settings = array(
		'yiqixueba_salt' => $salt,
		'yiqixueba_siteurlkey' => md5($_G['siteurl'].$salt),
		'yiqixueba_mainver' => '1.0',
	);
	C::t('common_setting')->update_batch($yiqixueba_settings);
}

$sitekey = C::t('common_setting')->fetch('yiqixueba_siteurlkey');

$pages = $tables = $templates = array();
$mokuaiver = array(
	'main' => '1.0',
	'server' => '1.0',
	'shop' => '1.0',
	'yqxb' => '1.0',
	'wxq123' => '1.0',
	'yikatong' => '1.0',
	'cheyouhui' => '1.0',
);
foreach($mokuaiver as $k=>$v ){
	update_pages($k,$v);
	update_table($k,$v);
	update_template($k,$v);
	update_lang($k,$v);
}
writelangfile($nplang);
C::t('common_setting')->update('yiqixueba_pages',serialize($pages));
C::t('common_setting')->update('yiqixueba_tables',serialize($tables));
C::t('common_setting')->update('yiqixueba_templates',serialize($templates));

unset($sitekey);
//更新page页面
function update_pages($mokuai,$ver){
	global $sitekey,$pages;
	$pages_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$ver.'/Controler';
	if ($handle = opendir($pages_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				$page_filename = md5($sitekey.$mokuai.'_'.substr($file,0,-4));
				$page_file = DISCUZ_ROOT.'source/plugin/yiqixueba/pages/'.$page_filename;
				$cache_filename = md5($sitekey.$page_filename.filemtime($page_file));
				$cache_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.$cache_filename;
				if ( filemtime($pages_dir."/".$file) > filemtime($page_file) || !file_exists($cache_file)){
					if(file_exists($cache_file)){
						@unlink($cache_file);
					}
					file_put_contents($page_file,convert_uuencode(file_get_contents($pages_dir."/".$file)));
					$cache_filename = md5($sitekey.$page_filename.filemtime($page_file));
					$cache_file = DISCUZ_ROOT.'source/plugin/yiqixueba/runtime/~'.$cache_filename;
					file_put_contents($cache_file,convert_uudecode(file_get_contents($page_file)));
					if( $file == 'basepage.php'){
						C::t('common_setting')->update('yiqixueba_basepage',$cache_filename);
					}
				}
				$pages[] = $mokuai.'_'.substr($file,0,-4);
			}
		}
	}
	return $pages;
}
//更新table页面
function update_table($mokuai,$ver){
	global $sitekey,$tables;
	$table_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$ver.'/Modal';
	if ($handle = opendir($table_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				$table_filename = 'y_'.md5($sitekey.$mokuai.'_'.substr($file,0,-4));
				$table_file = DISCUZ_ROOT.'source/plugin/yiqixueba/table/table_'.$table_filename.'.php';
				if(filesize($table_dir."/".$file) == 0){
					$neirong_temp = file_get_contents(MOKUAI_DIR.'/main/1.0/Modal/example.php');
					$neirong_temp = str_replace("example",substr($file,0,-4),$neirong_temp);
					file_put_contents($table_dir."/".$file,$neirong_temp);
				}
				//if($file != 'example.php'){
					if ( filemtime($table_dir."/".$file) > filemtime($table_file) || !file_exists($table_file)){
						$neirong_table = file_get_contents($table_dir."/".$file);
						$neirong_table = str_replace("class table_".substr($file,0,-4),"class table_".$table_filename,$neirong_table);
						$neirong_table = str_replace("\$this->_table = '".substr($file,0,-4)."'","\$this->_table = '".$table_filename."'",$neirong_table);
						file_put_contents($table_file,$neirong_table);
						C::t('#yiqixueba#y_'.md5($sitekey.$mokuai.'_'.substr($file,0,-4)))->create();
					}
					$tables[] = $mokuai.'_'.substr($file,0,-4);
				//}
			}
		}
	}
	return $tables;
}
//更新template页面
function update_template($mokuai,$ver){
	global $sitekey,$templates;
	$template_dir = MOKUAI_DIR.'/'.$mokuai.'/'.$ver.'/View';
	if ($handle = opendir($template_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && substr($file,0,1) != "." && $file != "index.html") {
				$template_file = DISCUZ_ROOT.'source/plugin/yiqixueba/template/'.md5($sitekey.$mokuai.'_'.substr($file,0,-4)).'.htm';
				if(filemtime($template_dir."/".$file)>filemtime($template_file) || !file_exists($template_file)){
					file_put_contents($template_file,file_get_contents($template_dir."/".$file));
				}
				$templates[] = $mokuai.'_'.substr($file,0,-4);
			}
		}
	}
	return $templates;
}
//更新模块的语言包
function update_lang($mokuainame,$mokuaiver){
	global $nplang;
	$mklang_file = MOKUAI_DIR.'/'.$mokuainame.'/'.$mokuaiver.'/lang.php';
	if(!file_exists($mklang_file)){
		file_put_contents($mklang_file,"<?php\nif(!defined('IN_DISCUZ')) {\n\texit('Access Denied');\n}\n\n\$plang['scriptlang'] = array(\n\t'' => '',\n);\n\$plang['templatelang'] = array(\n\t'' => '',\n);\n\$plang['installlang'] = array(\n\t'' => '',\n);\n\$plang['systemlang'] = array(\n\t'file' => array(\n\t\t'' => '',\n\t\t),\n\t);\n?>");
	}
	include_once $mklang_file;
	if(!$nplang){
		$nplang = $plang;
	}
		foreach($plang as $k => $v){
			if($k != 'systemlang'){
				foreach($v as $k1=>$v1){
					//$plang[$k][$k1] = diconv($v1,"UTF-8", $_G['charset']."//IGNORE");
				}
				$nplang[$k] = array_merge($nplang[$k],$plang[$k]);
			}else{
				foreach($v as $k1=>$v1 ){
					foreach($v1 as $k2=>$v2 ){
						//$plang[$k][$k1][$k2] = diconv($v2,"UTF-8", $_G['charset']."//IGNORE");
					}
					$nplang[$k][$k1] = array_merge($nplang[$k][$k1],$plang[$k][$k1]);
				}
			}
		}
	return $nplang;
}//end func

//写语言文件
function writelangfile($nplang){
	global $_G;
	$lang_file = DISCUZ_ROOT.'/data/plugindata/yiqixueba.lang.php';
	$lang_text = "<?php\n";
	foreach($nplang as $k=>$v ){
		$lang_text .= "\$".$k."['yiqixueba'] = array(\n";
		foreach($v as $k1=>$v1 ){
			if($k1){
				if($k != 'systemlang'){
						$lang_text .= "\t'".$k1."' => '".$v1."',\n";

				}else{
					$lang_text .= "\t'".$k1."' => array(\n";
					foreach($v1 as $k2=>$v2 ){
						if($k2){
							$lang_text .= "\t\t'".$k2."' => '".$v2."',\n";
						}
					}
					$lang_text .= "\t),\n";
				}
			}
		}
		$lang_text .= ");\n";

	}
	$lang_text .= "?>";

	file_put_contents($lang_file,diconv($lang_text,"UTF-8", $_G['charset']."//IGNORE"));
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