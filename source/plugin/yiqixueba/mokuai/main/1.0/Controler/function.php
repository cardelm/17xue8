<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//$pages =dunserialize(C::t('common_setting')->fetch('yiqixueba_pages'));
//$tables =dunserialize(C::t('common_setting')->fetch('yiqixueba_tables'));
//$templates =dunserialize(C::t('common_setting')->fetch('yiqixueba_templates'));
////
//得到已安装模块信息
function getmokuai(){
	return C::t(GM('main_mokuai'))->range();
}
//安装模块
function installmokuai($mokuai,$version){
}

//得到站长组信息
function getnodes(){
	global $_G;
}

//api_api_indata
function api_indata($apiaction,$indata=array()){
	global $_G,$server_siteurl;
	//if(fsockopen('www.wxq123.com', 80)){
		$indata['sitekey'] = getsitekey();
		$indata['siteurl'] = $_G['siteurl'];
		if($_G['charset']=='gbk') {
			foreach ( $indata as $k=>$v) {
				//$indata[$k] = diconv($v,$_G['charset'],'utf8');
			}
		}
		$indata = serialize($indata);
		$indata = base64_encode($indata);
		$api_url = $server_siteurl.'plugin.php?id=yiqixueba:api&apiaction='.$apiaction.'&indata='.$indata.'&sign='.md5(md5($indata));
		$xml = @file_get_contents($api_url);
		require_once libfile('class/xml');
		$outdata = is_array(xml2array($xml)) ? xml2array($xml) : $xml;
		return $outdata;
	//}else{
		//return false;
	//}
}//end func
//
function getmenus($menustype = 'admincp'){
	$outmenus = array();
	$pages =dunserialize(C::t('common_setting')->fetch('yiqixueba_pages'));
	foreach($pages as $k=>$v ){
		list($mokuai,$mtype,$mod) = explode("_",$v);
		if($mod && $mtype == $menustype){
			$outmenus[$mokuai][] = $mod;
		}
	}
	return $outmenus;
}


//得到站长key值
function getsitekey(){
}

//
function yiqixueba_serverurl(){
	$url = 'http://localhost/web/17xue8/plugin.php?id=yiqixueba&submod=main_mokuai';
	return $url;
}//end func
?>