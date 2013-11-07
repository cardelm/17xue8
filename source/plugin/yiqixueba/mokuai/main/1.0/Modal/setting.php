<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_setting extends discuz_table{

	public function __construct() {
		$this->_table = 'setting';
		$this->_pk    = 'skey';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`skey` varchar(255) NOT NULL default '',
			`svalue` text NOT NULL,
			PRIMARY KEY  (`skey`)
		";
		//////////////////////
		$query = DB::query("SHOW TABLES LIKE '%t'", array($this->_table));
		//$type = 'debug';
		if($type){
			if(DB::num_rows($query)) {
				DB::query('DROP TABLE '.DB::table($this->_table));
			}
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
	public function skey_exists($skey) {
		return DB::result_first('SELECT skey FROM %t WHERE skey=%s LIMIT 1', array($this->_table, $skey)) ? true : false;
	}
	public function fetch($skey) {
		return DB::result_first('SELECT svalue FROM %t WHERE skey=%s', array($this->_table, $skey));
	}
	public function update_batch($array) {
		$settings = array();
		foreach($array as $key => $value) {
			$key = addslashes($key);
			$value = addslashes(is_array($value) ? serialize($value) : $value);
			$settings[] = "('$key', '$value')";
		}
		if($settings) {
			return DB::query("REPLACE INTO ".DB::table(GM('main_setting'))." (`skey`, `svalue`) VALUES ".implode(',', $settings));
		}
		return false;
	}

}
?>