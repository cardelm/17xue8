<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$outdata = C::t(GM('server_goodssort'))->range();
?>