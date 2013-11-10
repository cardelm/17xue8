<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


$subops = array('list','install');
$subop = in_array($subop,$subops) ? $subop : $subops[0];

$mokuai_info = api_indata('server_mokuaiinfo');

unset($mokuai_info['main']);
foreach($mokuai_info as $k=>$v ){
	list($mokuai,$version) = explode($v);
}
if($subop == 'list') {
		showtips(lang('plugin/yiqixueba','mokuai_list_tips'));
		showformheader($this_page.'&subop=list');
		showtableheader(lang('plugin/yiqixueba','mokuai_list'));
		showsubtitle(array('', lang('plugin/yiqixueba','mokuai_name'),lang('plugin/yiqixueba','mokuai_version'),lang('plugin/yiqixueba','mokuai_price'),lang('plugin/yiqixueba','mokuai_installtime'),lang('plugin/yiqixueba','status'),''));
		foreach (C::t(GM('main_mokuai'))->range()  as $k => $row ){
			unset($mokuai_info[$row['biaoshi']][$row['version']]);
			if(!count($mokuai_info[$row['biaoshi']])){
				unset($mokuai_info[$row['biaoshi']]);
			}
			showtablerow('', array('class="td25"', 'style="width:120px"', 'class="td28"', '', '', '', ''), array(
				'<img src="'.cloudaddons_pluginlogo_url($row['biaoshi']).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
				'<span class="bold">'.$row['name'].'-V'.$row['version'].'</span>',
				$row['description'],
				$row['price'],
				dgmdate($row['createtime'],'dt'),
				($row['available'] ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=close&mokuaiid=$row[mokuaiid]\" >$lang[closed]</a>" : "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=open&mokuaiid=$row[mokuaiid]\">$lang[enable]</a>")."&nbsp;&nbsp;".
				(intval(end(array_keys($mokuai_info[$row['biaoshi']]))) > intval($row['version']) ? "<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=update&mokuaiid=$row[mokuaiid]\">$lang[plugins_config_upgrade]</a>&nbsp;&nbsp;": '').
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=uninstall&mokuaiid=$row[mokuaiid]\">$lang[plugins_config_uninstall]</a>&nbsp;&nbsp;",
			));
		}
		foreach ($mokuai_info as $k => $v ){
			$row = end($v);
			$key = end(array_keys($v));
			showtablerow('', array('class="td25"', 'style="width:120px"', 'class="td28"', '', '', '', ''), array(
				'<img src="'.cloudaddons_pluginlogo_url($k).'" onerror="this.src=\'static/image/admincp/plugin_logo.png\';this.onerror=null" width="40" height="40" align="left" />',
				'<span class="bold">'.$row['name'].'-V'.$key.'</span>',
				$row['description'],
				$row['price'] ? $row['price'].lang('plugin/yiqixueba','rmb') : lang('plugin/yiqixueba','mianfei'),
				'',
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subop=install&mokuainame=".$k."_".$key."\">$lang[plugins_config_install]</a>"
			));
		}
		showtablefooter();
		showformfooter();
}elseif($subop == 'install'){
	$data['mokuai'] = getgpc('mokuainame');
	$installmokuai = api_indata('server_installmokuai',$data);
	dump($installmokuai);
}
$data = array(
	'biaoshi' => 'server',
	'version' => '1.0',
	'available' => 1,
	'createtime' => time(),
	'updatetime' => '',
	'displayorder' => 0,
	'name' => '服务端',
	'price' => 50000,
	'description' => '平台的模块设计部分',
);
//C::t(GM('main_mokuai'))->insert($data);
?>


