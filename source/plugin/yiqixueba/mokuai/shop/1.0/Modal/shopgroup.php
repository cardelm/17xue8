<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_shopgroup extends discuz_table{

	public function __construct() {
		$this->_table = 'shopgroup';
		$this->_pk    = 'shopgroupid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`shopgroupid` smallint(6) unsigned NOT NULL auto_increment,
			`shopgroupname` char(50) character set gbk NOT NULL,
			`inshoufei` int(10) unsigned NOT NULL,
			`inshoufeiqixian` int(10) unsigned NOT NULL,
			`shopgroupdescription` varchar(255) character set gbk NOT NULL,
			`cardfeiyong` int(10) NOT NULL,
			`cardpice` int(10) unsigned NOT NULL,
			`status` tinyint(1) NOT NULL,
			`shopgroupico` varchar(255) character set gbk NOT NULL,
			`xiaofei` varchar(255) character set gbk NOT NULL,
			`zhanghaoyue` int(10) NOT NULL,
			`zhanghaojifen` int(10) NOT NULL,
			`xiaofeitypeshenhe` tinyint(1) NOT NULL,
			`dianyuanshenhe` tinyint(1) NOT NULL,
			`dianzhang` varchar(255) character set gbk NOT NULL,
			`caiwu` varchar(255) character set gbk NOT NULL,
			`shouyin` varchar(255) character set gbk NOT NULL,
			`enfendian` tinyint(1) NOT NULL,
			`enshopnum` int(10) NOT NULL,
			`contractsample` char(100) character set gbk NOT NULL,
			`isshop` varchar(255) NOT NULL,
			PRIMARY KEY  (`shopgroupid`)
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
	public function fetch_by_shopgroupid($shopgroupid) {
		$mokuai_info = array();
		if($mokuaiid) {
			$mokuai_info = DB::fetch_first('SELECT * FROM %t WHERE mokuaiid=%s', array($this->_table, $mokuaiid));
		}
		return $mokuai_info;
	}

}
?>