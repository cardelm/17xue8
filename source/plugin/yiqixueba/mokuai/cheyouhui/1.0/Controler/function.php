<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//
function fieldhtml($type,$data){
	if($type=='text'){
	}
	return $type;
}//end func

//
function fieldparameterhtml($type,$data){
	if($type=='num'){
		$htmlout = lang('plugin/yiqixueba','maxnum');
		$htmlout .= '<input type="text" name="parameter[maxnum]" value="'.$data['maxnum'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','minnum');
		$htmlout .= '<input type="text" name="parameter[minnum]" value="'.$data['minnum'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','inputnum');
		$htmlout .= '<input type="text" name="parameter[inputnum]" value="'.$data['inputnum'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','defaultvalue');
		$htmlout .= '<input type="text" name="parameter[defaultvalue]" value="'.$data['defaultvalue'].'" size="12"><br /><br />';
	}elseif($type=='text'){
		$htmlout = lang('plugin/yiqixueba','maxtext');
		$htmlout .= '<input type="text" name="parameter[maxtext]" value="'.$data['maxtext'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','mintext');
		$htmlout .= '<input type="text" name="parameter[mintext]" value="'.$data['mintext'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','inputtext');
		$htmlout .= '<input type="text" name="parameter[inputtext]" value="'.$data['inputtext'].'" size="2"><br /><br />';
		$htmlout .= lang('plugin/yiqixueba','defaultvalue');
		$htmlout .= '<input type="text" name="parameter[defaultvalue]" value="'.$data['defaultvalue'].'" size="12"><br /><br />';
	}
	return $htmlout;
}//end func
?>