<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_mokuai extends discuz_table{

	public function __construct() {
		$this->_table = 'mokuai';
		$this->_pk    = 'mokuaiid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		$fields = "
		  `mokuaiid` smallint(6) NOT NULL,
		  `available` tinyint(1) NOT NULL default '0',
		  `adminid` tinyint(1) unsigned NOT NULL default '0',
		  `name` varchar(40) NOT NULL default '',
		  `identifier` varchar(40) NOT NULL default '',
		  `version` varchar(20) NOT NULL default '',
		  `description` varchar(255) NOT NULL default '',
		  `datatables` varchar(255) NOT NULL default '',
		  `directory` varchar(100) NOT NULL default '',
		  `copyright` varchar(100) NOT NULL default '',
		  `modules` text NOT NULL,
		  `setting` text NOT NULL,
		  `pages` text NOT NULL,
		  `temps` text NOT NULL,
		  `menus` text NOT NULL,
		  `displayorder` smallint(6) NOT NULL,
		  `mokuaikey` varchar(32) NOT NULL,
		  `updatetime` int(10) unsigned NOT NULL,
		  PRIMARY KEY  (`mokuaiid`,`identifier`),
		  UNIQUE KEY `identifier` (`identifier`)
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

}
?>