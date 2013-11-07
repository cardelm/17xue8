<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$server_siteurl = 'http://localhost/web/17xue8/';

//得到前台模板
function GT($temp_name){
	global $temp;
	list($mk,$tempname) = explode("_",$temp_name);
	return GV($mk.'_yiqixueba_'.$temp.'_'.$tempname);
}
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

//得到站长key值
function getsitekey(){
	return C::t('common_setting')->fetch('yiqixueba_siteurlkey');
}

//
function yiqixueba_serverurl(){
	$url = 'http://localhost/web/17xue8/plugin.php?id=yiqixueba&submod=main_mokuai';
	return $url;
}//end func

//
function getadmincpmenus($type = 1){
	$admincpmenus = array();
	if($type == 1){
		foreach(C::t(GM('main_menus'))->fetch_all('admincp',0) as $k=>$v ){
			$topid = $v['name'];
			$admincpmenus[$topid]['title'] = $v['title'];
			$admincpmenus[$topid]['displayorder'] = $v['displayorder'];
			foreach(C::t(GM('main_menus'))->fetch_all('admincp',$v['menuid']) as $k1=>$v1 ){
				$subid = $v1['name'];
				$admincpmenus[$topid]['submenu'][$subid]['title'] = $v1['title'];
				$admincpmenus[$topid]['submenu'][$subid]['displayorder'] = $v1['displayorder'];
				$admincpmenus[$topid]['submenu'][$subid]['modfile'] = $v1['modfile'];
			}
		}
	}
	if($type == 2){
		require_once libfile('class/xml');
		$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/mokuai.xml"));
		foreach($mokuais as $k=>$v ){
			$topid = $k;
			$admincpmenus[$topid]['title'] = $v['name'];
			$admincpmenus[$topid]['displayorder'] = $v['displayorder'];
			foreach($v['version'] as $k2=>$v2 ){
				$node = xml2array(file_get_contents(MOKUAI_DIR."/server/1.0/Data/node_".$k."_".$k2.".xml"));
				foreach($node as $k1=>$v1 ){
					list($mt,$mn) = explode("_",$k1);
					if($mt == 'admincp' && $mn && $v1['menu']){
						$subid =$mn;
						$admincpmenus[$topid]['submenu'][$subid]['title'] = $v1['title'];
						$admincpmenus[$topid]['submenu'][$subid]['displayorder'] = $v1['displayorder'];
						$admincpmenus[$topid]['submenu'][$subid]['modfile'] = $k.'_'.$k1;
					}
				}
			}
			$admincpmenus[$topid]['submenu'] = array_sort($admincpmenus[$topid]['submenu'],'displayorder','asc');
		}
		$admincpmenus = array_sort($admincpmenus,'displayorder','asc');
	}
	return $admincpmenus;
}
//
function getmembernav($type = 1){
	global $_G;
	$usernavs = array();
	if($type==1){
		foreach(C::t(GM('main_menus'))->fetch_all('member',0) as $mk=>$row ){
			$topid = $row['name'];
			$usernavs[$topid]['name'] = $row['name'];
			$usernavs[$topid]['title'] = $row['title'];
			$usernavs[$topid]['displayorder'] = $row['displayorder'];
			foreach(C::t(GM('main_menus'))->fetch_all('member',$row['menuid']) as $kk => $subrow ){
				$subid = $subrow['name'];
				$usernavs[$topid]['submenu'][$subid]['name'] = $subrow['name'];
				$usernavs[$topid]['submenu'][$subid]['title'] = $subrow['title'];
				$usernavs[$topid]['submenu'][$subid]['displayorder'] = $subrow['displayorder'];
				$usernavs[$topid]['submenu'][$subid]['modfile'] = $subrow['modfile'];
			}
		}
	}
	if($type==2){
		require_once libfile('class/xml');
		$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
		foreach($mokuais as $k=>$v ){
			$node = xml2array(file_get_contents(MOKUAI_DIR."/".$k."/".$v['currentversion']."/node.xml"));
			foreach($node as $k1=>$v1 ){
				list($mt,$mn) = explode("_",$k1);

				if($mt == 'member' && $mn && $v1['menu']){

					$topid = $k;
					$usernavs[$topid]['name'] = $k;
					$usernavs[$topid]['title'] = $v['name'];
					$usernavs[$topid]['displayorder'] = $v['displayorder'];
					$subid =$mn;
					$usernavs[$topid]['submenu'][$subid]['name'] = $mn;
					$usernavs[$topid]['submenu'][$subid]['title'] = $v1['title'];
					$usernavs[$topid]['submenu'][$subid]['displayorder'] = $v1['displayorder'];
					$usernavs[$topid]['submenu'][$subid]['modfile'] = $k.'_'.$k1;
				}
			}
			$usernavs[$topid]['submenu'] = array_sort($usernavs[$topid]['submenu'],'displayorder','asc');
		}
		$usernavs = array_sort($usernavs,'displayorder','asc');
	}
	return $usernavs;
}
//
function getyiqixuebanav($type = 1){
	global $_G;
	$yiqixueba = array();
	if($type==1){
		foreach(C::t(GM('main_menus'))->fetch_all('yiqixueba',0) as $mk=>$row ){
			$topid = $row['name'];
			$yiqixueba[$topid]['name'] = $row['name'];
			$yiqixueba[$topid]['title'] = $row['title'];
			$yiqixueba[$topid]['displayorder'] = $row['displayorder'];
			foreach(C::t(GM('main_menus'))->fetch_all('yiqixueba',$row['menuid']) as $kk => $subrow ){
				$subid = $subrow['name'];
				$yiqixueba[$topid]['submenu'][$subid]['name'] = $subrow['name'];
				$yiqixueba[$topid]['submenu'][$subid]['title'] = $subrow['title'];
				$yiqixueba[$topid]['submenu'][$subid]['displayorder'] = $subrow['displayorder'];
				$yiqixueba[$topid]['submenu'][$subid]['modfile'] = $subrow['modfile'];
			}
		}
	}
	if($type==2){
		require_once libfile('class/xml');
		$mokuais = xml2array(file_get_contents(MOKUAI_DIR."/mokuai.xml"));
		foreach($mokuais as $k=>$v ){
			$node = xml2array(file_get_contents(MOKUAI_DIR."/".$k."/".$v['currentversion']."/node.xml"));
			foreach($node as $k1=>$v1 ){
				list($mt,$mn) = explode("_",$k1);

				if($mt == 'yiqixueba' && $mn && $v1['menu']){

					$topid = $k;
					$yiqixueba[$topid]['name'] = $k;
					$yiqixueba[$topid]['title'] = $v['name'];
					$yiqixueba[$topid]['displayorder'] = $v['displayorder'];
					$subid =$mn;
					$yiqixueba[$topid]['submenu'][$subid]['name'] = $mn;
					$yiqixueba[$topid]['submenu'][$subid]['title'] = $v1['title'];
					$yiqixueba[$topid]['submenu'][$subid]['displayorder'] = $v1['displayorder'];
					$yiqixueba[$topid]['submenu'][$subid]['modfile'] = $k.'_'.$k1;
				}
			}
			$yiqixueba[$topid]['submenu'] = array_sort($yiqixueba[$topid]['submenu'],'displayorder','asc');
		}
		$yiqixueba = array_sort($yiqixueba,'displayorder','asc');
	}
	return $yiqixueba;
}
?>