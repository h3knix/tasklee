<?php
	
	class app {
		public static $pdo_opts = array(
			//my php version happend to be pre 5.3.6 and i want utf8
		    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
		
		public static $db;
		public static function db_connect() {
			self::$db = new PDO(DB_DSN, DB_USER, DB_PASS, self::$pdo_opts);
			return self::$db;
		}
	}
