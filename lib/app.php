<?php
	
	class app {
		public static $conf;
		
		public static $pdo_opts = array(
			//my php version happend to be pre 5.3.6 and i want utf8
		    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		
		public static $db;
		public static function db_connect($conf=null) {
			if ( ! $conf ) $conf = self::$conf;
			self::$db = new PDO($conf->db_dsn, $conf->db_user, $conf->db_pass, self::$pdo_opts);
			return self::$db;
		}
	}
