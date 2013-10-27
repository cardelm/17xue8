<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//dump($pages);
//dump($tables);
//dump($templates);
foreach($tables as $k=>$v ){
	dump($v);
	dump(GM($v));
}
//dump(file_exists(GC('cheyouhui_ajax')));
dump($mokuais);
?>