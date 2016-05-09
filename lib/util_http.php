<?php
	
	class util_http {
		
		public static function set_header_code($code,$str) {
			return header($_SERVER['SERVER_PROTOCOL'] .' '. $str, true, $code);
		}
		public static function set_header_bad_request() {
			self::set_header_code(400,'Bad Request');
		}
		public static function set_header_server_error() {
			self::set_header_code(500,'Internal Server Error');
		}
		public static function set_header_notfound_error() {
			self::set_header_code(404,'Not Found');
		}
		
		public static function set_header_nocache() {
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
			header('Pragma: no-cache');
		}
		
		public static function route_path($web_root) {
			$route = array();
			preg_match('/^'. preg_quote($web_root,'/') .'([^\?]*)/',$_SERVER['REQUEST_URI'],$route);
			if ( is_array($route) && isset($route[1]) ) {
				//just in case, get rid of whitespace before and after trimming forwardslash
				$route = trim(trim(trim($route[1]),'/'));
				$route = explode('/',$route);
			} else $route = array();
			return $route;
		}
		
		private static $request_method = null;
		public static function request_method() {
			if ( self::$request_method != null ) return self::$request_method;
			
			self::$request_method = '';
			if ( isset($_POST['_method']) ) self::$request_method = $_POST['_method'];
			else if ( isset($_SERVER['REQUEST_METHOD']) ) self::$request_method = $_SERVER['REQUEST_METHOD'];
			self::$request_method = strtolower(self::$request_method);
			
			return self::$request_method;
		}
		
		public static function json_input_payload() {
			return json_decode(file_get_contents("php://input"));
		}
	}
