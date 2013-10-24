<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($ajaxtype == 'fieldparameter'){
	$fieldclass = getgpc('fieldclass');
	if($fieldclass == 'num'){
	}
	$ajaxdata = fieldparameterhtml($fieldclass,$data);
}
?>