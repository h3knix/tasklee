<?php
	
	class json_return {
		
		public $data;
		
		public function __construct() {
			$this->data = array();
		}
		
		public function output() {
			echo json_encode($this->data);
		}
		
		public function add_error($error_str) {
			if ( ! isset($this->data['errors']) ) $this->data['errors'] = array();
			$this->data['errors'][] = $error_str;
		}
	}
