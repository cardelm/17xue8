<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_goodssort extends discuz_table{

	public function __construct() {
		$this->_table = 'goodssort';
		$this->_pk    = 'goodssortid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`goodssortid` smallint(6) unsigned NOT NULL auto_increment,
			`upmokuai` smallint(6) NOT NULL,
			`sortname` char(20) character set gbk NOT NULL,
			`sorttitle` char(20) character set gbk NOT NULL,
			`sortlevel` smallint(6) NOT NULL,
			`sortupid` smallint(6) NOT NULL,
			`displayorder` smallint(6) NOT NULL,
			`upids` text character set gbk NOT NULL,
			PRIMARY KEY  (`goodssortid`)
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
	public function fetch_by_upids($sortupid) {
		if($sortupid) {
			$sort_info = DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." WHERE sortupid = ".$sortupid." order by concat(upids,'-',goodssortid) asc");
		}else{
			$sort_info = DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." order by concat(upids,'-',goodssortid) asc");
		}
		return $sort_info;
	}
	public function fetch_result_upids_by_goodssortid($goodssortid) {
		if($sortupid) {
			return DB::result_first("SELECT upids FROM ".DB::table($this->_table)." WHERE goodssortid=".intval($goodssortid));
		}
	}
	public function fetch_result_sortlevel_by_goodssortid($goodssortid) {
		if($sortupid) {
			return DB::result_first("SELECT sortlevel FROM ".DB::table($this->_table)." WHERE goodssortid=".intval($goodssortid));
		}
	}

}
?>