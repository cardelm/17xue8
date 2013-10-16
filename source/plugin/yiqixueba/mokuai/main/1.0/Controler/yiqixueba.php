<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$submod = getgpc('submod');

dump('yiqixueba:'.yiqixueba_template('main_index'));
include template('yiqixueba:'.yiqixueba_template('main_index'));
?>