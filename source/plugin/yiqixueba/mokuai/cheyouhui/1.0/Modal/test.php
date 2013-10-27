<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_test extends discuz_table{

	public function __construct() {
		$this->_table = 'test';
		$this->_pk    = 'testid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`testid` smallint(6) NOT NULL auto_increment,
			`testname` varchar(40) NOT NULL default '',
			`testtitle` varchar(40) NOT NULL default '',
			`testsort` varchar(40) NOT NULL default '',
			`testimages` varchar(40) NOT NULL default '',
			`description` text NOT NULL,
			`status` tinyint(1) NOT NULL default '0',
			`createtime` int(10) unsigned NOT NULL,
			`updatetime` int(10) unsigned NOT NULL,
			PRIMARY KEY  (`testid`)
		";
		//////////////////////
		$query = DB::query("SHOW TABLES LIKE '%t'", array($this->_table));
		if(DB::num_rows($query) == 1) {
			DB::query('DROP TABLE '.DB::table($this->_table));
		}
		//if(DB::num_rows($query) != 1) {
			$create_table_sql = "CREATE TABLE ".DB::table($this->_table)." ($fields) TYPE=MyISAM;";
			$db = DB::object();
			$create_table_sql = $this->syntablestruct($create_table_sql, $db->version() > '4.1', $_G['config']['db']['1']['dbcharset']);
			DB::query($create_table_sql);
		//}
	}

	public function syntablestruct($sql, $version, $dbcharset) {

		if(strpos(trim(substr($sql, 0, 18)), 'CREATE TABLE') === FALSE) {
			return $sql;
		}

		$sqlversion = strpos($sql, 'ENGINE=') === FALSE ? FALSE : TRUE;

		if($sqlversion === $version) {

			return $sqlversion && $dbcharset ? preg_replace(array('/ character set \w+/i', '/ collate \w+/i', "/DEFAULT CHARSET=\w+/is"), array('', '', "DEFAULT CHARSET=$dbcharset"), $sql) : $sql;
		}

		if($version) {
			return preg_replace(array('/TYPE=HEAP/i', '/TYPE=(\w+)/is'), array("ENGINE=MEMORY DEFAULT CHARSET=$dbcharset", "ENGINE=\\1 DEFAULT CHARSET=$dbcharset"), $sql);

		} else {
			return preg_replace(array('/character set \w+/i', '/collate \w+/i', '/ENGINE=MEMORY/i', '/\s*DEFAULT CHARSET=\w+/is', '/\s*COLLATE=\w+/is', '/ENGINE=(\w+)(.*)/is'), array('', '', 'ENGINE=HEAP', '', '', 'TYPE=\\1\\2'), $sql);
		}
	}
	public function fetch_by_testid($testid) {
		$mokuai_info = array();
		if($mokuaiid) {
			$mokuai_info = DB::fetch_first('SELECT * FROM %t WHERE mokuaiid=%s', array($this->_table, $mokuaiid));
		}
		return $mokuai_info;
	}

}
?>