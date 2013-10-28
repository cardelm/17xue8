<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_shopsort extends discuz_table{

	public function __construct() {
		$this->_table = 'shopsort';
		$this->_pk    = 'shopsortid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`shopsortid` smallint(6) unsigned NOT NULL auto_increment,
			`upmokuai` smallint(6) NOT NULL,
			`sortname` char(20) character set gbk NOT NULL,
			`sorttitle` char(20) character set gbk NOT NULL,
			`sortlevel` smallint(6) NOT NULL,
			`sortupid` smallint(6) NOT NULL,
			`displayorder` smallint(6) NOT NULL,
			`upids` text character set gbk NOT NULL,
			PRIMARY KEY  (`shopsortid`)
		";
		//////////////////////
		$query = DB::query("SHOW TABLES LIKE '%t'", array($this->_table));
		if(DB::num_rows($query) == 1) {
			//DB::query('DROP TABLE '.DB::table($this->_table));
		}
		if(DB::num_rows($query) != 1) {
			$create_table_sql = "CREATE TABLE ".DB::table($this->_table)." ($fields) TYPE=MyISAM;";
			$db = DB::object();
			$create_table_sql = $this->syntablestruct($create_table_sql, $db->version() > '4.1', $_G['config']['db']['1']['dbcharset']);
			DB::query($create_table_sql);
		}
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
	public function fetch_by_shopsortid($shopsortid) {
		$mokuai_info = array();
		if($mokuaiid) {
			$mokuai_info = DB::fetch_first('SELECT * FROM %t WHERE mokuaiid=%s', array($this->_table, $mokuaiid));
		}
		return $mokuai_info;
	}

}
?>