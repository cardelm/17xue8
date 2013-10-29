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
			`mokuaiid` smallint(6) NOT NULL auto_increment,
			`available` tinyint(1) NOT NULL default '0',
			`name` varchar(40) NOT NULL default '',
			`biaoshi` varchar(40) NOT NULL default '',
			`version` varchar(20) NOT NULL default '',
			`displayorder` smallint(6) NOT NULL,
			`mokuaikey` varchar(32) NOT NULL,
			`updatetime` int(10) unsigned NOT NULL,
			`createtime` int(10) unsigned NOT NULL,
			PRIMARY KEY  (`mokuaiid`)
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

	public function fetch_all_mokuai() {
		return DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." WHERE currentversion = 1 group by biaoshi order by displayorder asc");
	}
	public function fetch_all_ver($mokuai) {
		return DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." WHERE biaoshi = '".$mokuai."' order by createtime asc");
	}
	public function fetch_by_mokuaiid($mokuaiid) {
		$mokuai_info = array();
		if($mokuaiid) {
			$mokuai_info = DB::fetch_first('SELECT * FROM %t WHERE mokuaiid=%s', array($this->_table, $mokuaiid));
		}
		return $mokuai_info;
	}
	public function fetch_by_mkcount($mokuai) {
		$mokuai_count = 0;
		if($mokuai) {
			$mokuai_count = DB::result_first('SELECT count(*) FROM %t WHERE biaoshi=%s', array($this->_table, $mokuai));
		}
		return $mokuai_count;
	}
	public function update_curver($biaoshi,$version='') {
		if($biaoshi) {
			if($version){
				DB::update($this->_table,array('currentversion'=>1),array('biaoshi'=>$biaoshi,'version'=>$version));
			}else{
				DB::update($this->_table,array('currentversion'=>0),array('biaoshi'=>$biaoshi));
				//DB::update($this->_table,array('currentversion'=>1),array('biaoshi'=>$biaoshi));
			}
		}
	}
	public function update_all($data) {
		if($data) {
			DB::update($this->_table,$data);
		}
	}
}
?>