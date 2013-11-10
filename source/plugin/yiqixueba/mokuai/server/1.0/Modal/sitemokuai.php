<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_sitemokuai extends discuz_table{

	public function __construct() {
		$this->_table = 'sitemokuai';
		$this->_pk    = 'sitemokuaiid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		$fields = "
			`sitemokuaiid` int(8) NOT NULL auto_increment,
			`siteid` varchar(255) NOT NULL,
			`salt` char(6) NOT NULL,
			`mokuaikey` char(32) NOT NULL,
			`installtime` int(10) unsigned NOT NULL,
			`updatetime` int(10) unsigned NOT NULL,
			PRIMARY KEY  (`sitemokuaiid`)
		";
		$query = DB::query("SHOW TABLES LIKE '%t'", array($this->_table));
		//$type = 'debug';
		if($type){
			DB::query('DROP TABLE '.DB::table($this->_table));
			$create_table_sql = "CREATE TABLE ".DB::table($this->_table)." ($fields) TYPE=MyISAM;";
			$db = DB::object();
			$create_table_sql = $this->syntablestruct($create_table_sql, $db->version() > '4.1', $_G['config']['db']['1']['dbcharset']);
			DB::query($create_table_sql);
		}else{
			if(DB::num_rows($query) != 1) {
				$create_table_sql = "CREATE TABLE ".DB::table($this->_table)." ($fields) TYPE=MyISAM;";
				$db = DB::object();
				$create_table_sql = $this->syntablestruct($create_table_sql, $db->version() > '4.1', $_G['config']['db']['1']['dbcharset']);
				DB::query($create_table_sql);
			}
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
	public function fetch_by_siteurl($siteurl) {
		$siteinfo = array();
		if($siteurl) {
			$siteinfo = DB::fetch_first('SELECT * FROM %t WHERE siteurl=%s', array($this->_table, $siteurl));
		}
		return $siteinfo;
	}

}
?>