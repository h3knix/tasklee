<?php
	
	class conf {
		public $web_root = '/tasklee/';
		
		public $db_dsn = 'mysql:host=localhost;dbname=tasklee;charset=utf8';
		public $db_user = 'tasklee';
		public $db_pass = '';
		
		public $pdo_opts = array(
			//my php version happend to be pre 5.3.6 and i want utf8
		    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		);
	}
