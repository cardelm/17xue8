<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_cardcat extends discuz_table{

	public function __construct() {
		$this->_table = 'cardcat';
		$this->_pk    = 'cardcatid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`cardcatid` smallint(6) unsigned NOT NULL auto_increment,
			`cardcatname` char(50) character set gbk NOT NULL,
			`cardcatdescription` text character set gbk NOT NULL,
			`cardjine` int(10) unsigned NOT NULL,
			`cardpice` int(10) unsigned NOT NULL,
			`cardyouxiaoqi` int(10) unsigned NOT NULL,
			`carddzyouxiaoqi` char(10) character set gbk NOT NULL,
			`status` tinyint(1) NOT NULL,
			`cardcatico` varchar(255) character set gbk NOT NULL,
			`cardkaishi` int(10) unsigned NOT NULL,
			`cardtype` char(20) character set gbk NOT NULL,
			`cardjifen` int(10) NOT NULL,
			`cardqingling` varchar(255) character set gbk NOT NULL,
			`shopid` mediumint(8) NOT NULL,
			PRIMARY KEY  (`cardcatid`)
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

}
?>