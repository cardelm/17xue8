<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_field extends discuz_table{

	public function __construct() {
		$this->_table = 'field';
		$this->_pk    = 'fieldname';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`fieldname` char(40) NOT NULL,
			`fieldtitle` char(40) NOT NULL,
			`fieldtips` char(255) NOT NULL,
			`fieldclass` char(40) NOT NULL,
			`fieldparameter` text NOT NULL,
			`displayorder` smallint(6) NOT NULL,
			`isrequired` tinyint(1) NOT NULL,
			PRIMARY KEY  (`fieldname`)
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

	//
	public function fetch_all_by_fieldtype($fieldtype) {
		return DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." WHERE fieldname LIKE '".$fieldtype."_%' order by displayorder asc");
	}
	//
	public function fetch_all_by_fieldname($fieldname) {
		return DB::fetch_first("SELECT * FROM ".DB::table($this->_table)." WHERE fieldname = '".$fieldname."'");
	}
	//
	public function count_by_fieldtype($fieldtype) {
		return DB::result_first("SELECT count(*) FROM ".DB::table($this->_table)." WHERE fieldname LIKE '".$fieldtype."_%'");
	}
	public function fetch_all_by_search($fieldtype = 'jsz', $start = 0, $limit = 0){
		return DB::fetch_all("SELECT * FROM ".DB::table($this->_table)." WHERE fieldname LIKE '".$fieldtype."_%' ORDER BY displayorder DESC ".DB::limit($start, $limit));
	}
	public function update_by_fieldname($fieldname, $data){
		return DB::update($this->_table,$data,array('fieldname'=>$fieldname));
	}
}
?>