<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_shop extends discuz_table{

	public function __construct() {
		$this->_table = 'shop';
		$this->_pk    = 'shopid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`shopid` mediumint(8) unsigned NOT NULL auto_increment,
			`shopname` char(50) character set gbk NOT NULL,
			`shopalias` char(40) character set gbk NOT NULL,
			`shopvideo` varchar(255) character set gbk NOT NULL,
			`shoplocation` char(40) character set gbk NOT NULL,
			`dist` char(50) character set gbk NOT NULL,
			`comy` char(50) character set gbk NOT NULL,
			`shopintroduction` varchar(255) character set gbk NOT NULL,
			`shopinformation` text character set gbk NOT NULL,
			`shoprecommend` smallint(3) NOT NULL,
			`shoplevel` smallint(3) NOT NULL,
			`uid` mediumint(8) NOT NULL,
			`shopdianyuan` text character set gbk NOT NULL,
			`createtime` int(10) unsigned NOT NULL,
			`renlingtime` int(10) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`shopsort` smallint(3) NOT NULL,
			`shoplogo` varchar(100) character set gbk NOT NULL,
			`address` varchar(100) character set gbk NOT NULL,
			`phone` varchar(20) character set gbk NOT NULL,
			`lianxiren` varchar(20) character set gbk NOT NULL,
			`qq` varchar(20) character set gbk NOT NULL,
			`upmokuai` varchar(255) NOT NULL,
			`businessid` mediumint(8) unsigned NOT NULL,
			`upshopid` mediumint(8) unsigned NOT NULL,
			PRIMARY KEY  (`shopid`)
		";
		//////////////////////
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
	public function fetch_by_shopid($shopid) {
		$mokuai_info = array();
		if($mokuaiid) {
			$mokuai_info = DB::fetch_first('SELECT * FROM %t WHERE mokuaiid=%s', array($this->_table, $mokuaiid));
		}
		return $mokuai_info;
	}

}
?>