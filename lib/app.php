<?php
	
	class app {
		public static $conf;
		
		public static $db;
		public static function db_connect($conf=null) {
			if ( ! $conf ) $conf = self::$conf;
			self::$db = new PDO($conf->db_dsn, $conf->db_user, $conf->db_pass, $conf->pdo_opts);
			return self::$db;
		}
	}
