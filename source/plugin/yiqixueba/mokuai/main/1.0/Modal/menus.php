<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class table_menus extends discuz_table{

	public function __construct() {
		$this->_table = 'menus';
		$this->_pk    = 'menuid';
		parent::__construct();
	}

	public function create() {
		global $_G;
		//////////////////////////
		$fields = "
			`menuid` smallint(6) NOT NULL auto_increment,
			`type` enum('yiqixueba','member','admincp') NOT NULL,
			`upid` smallint(6) NOT NULL,
			`name` char(20) NOT NULL,
			`title` char(20) NOT NULL,
			`level` tinyint(1) NOT NULL,
			`modfile` varchar(255) NOT NULL,
			`status` tinyint(1) NOT NULL,
			`displayorder` smallint(3) NOT NULL,
			PRIMARY KEY  (`menuid`)
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
	public function fetch_all($type,$upid,$mod = 'main'){
		$menus = array();
		if($type){
			$menus = DB::fetch_all('SELECT * FROM %t WHERE upid='.$upid.' and '.($mod == 'main' ? ' status = 1 and ' : '').' type=%s ORDER BY displayorder asc', array($this->_table, $type));
		}
		return $menus;
	}//end func

	//
	public function delete_by_menuid($menutype, $ids){
		$ids = dintval($ids, is_array($ids) ? true : false);
		if($ids) {
			return DB::delete($this->_table, DB::field('menuid', $ids).' AND '.DB::field('type', $menutype));
		}
		return 0;
	}//end func
}
?>