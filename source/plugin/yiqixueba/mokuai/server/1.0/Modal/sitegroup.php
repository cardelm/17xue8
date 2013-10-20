<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_sitegroup extends discuz_table{

	public function __construct() {
		$this->_table = 'sitegroup';
		$this->_pk    = 'sitegroupid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		$fields = "
			`sitegroupid` smallint(6) NOT NULL auto_increment,
			`sitegroupname` char(40) NOT NULL,
			`createtime` int(10) NOT NULL,
			`updatetime` int(10) unsigned NOT NULL,
			`versions` text NOT NULL,
			`status` tinyint(1) NOT NULL,
			`systemgroup` tinyint(1) NOT NULL,
			PRIMARY KEY  (`sitegroupid`)
		";
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

	public function fetch_by_sitegroupid($sitegroupid) {
		$sitegroup_info = array();
		if($sitegroupid) {
			$sitegroup_info = DB::fetch_first('SELECT * FROM %t WHERE sitegroupid=%s', array($this->_table, $sitegroupid));
		}
		return $sitegroup_info;
	}
	public function fetch_all() {
		return DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." order by sitegroupid asc");
	}

}
?>