<?php
	
	class util_db {
		public static $date_zero = '0000-00-00';
		public static $datetime_zero = '0000-00-00 00:00:00';
		
		public static function is_date_zero($str) {
			if ( ! $str ) return true;
			if ( $str == self::$date_zero ) return true;
			return false;
		}
		public static function is_datetime_zero($str) {
			if ( self::is_date_zero($str) ) return true;
			if ( $str == self::$datetime_zero ) return true;
			return false;
		}
		
		public static function datetime_format($int) {
			//if it's not a number, lets go ahead and give back a zero
			if ( ! is_numeric($int) ) return self::$datetime_zero;
			
			$int = intval($int);
			if ( ! $int ) return self::$datetime_zero;
			return date('Y-m-d H:i:s',$int);
		}
		
	}
