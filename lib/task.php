<?php
	
	class task {
		public $id;
		public $name = '';
		public $is_complete = 0;
		public $completed = 0;
		public $created = 0;
		public $modified = 0;
		
		public $was_previously_completed = false;
		
		public function __construct() {
			//pdo sets properties before constructor is called, so this will work
			if ( $this->is_complete ) $this->was_previously_completed = true;
			
			//convert datetime from db to integers
			foreach(array('completed','created','modified') as $d) {
				if ( ! $this->{$d} || db_util::is_datetime_zero($this->{$d}) ) $this->{$d} = 0;
				else $this->{$d} = strtotime($this->{$d});
			}
		}
		
		public function save() {
			$this->modified = time();
			if ( $this->id ) {
				if ( $this->complete && ( ! $this->was_previously_completed || ! $this->completed ) ) {
					$this->completed = time();
				}
				$stmt = app::$db->prepare(
					'update `task` set `name` = :name,`is_complete` = :is_complete,`completed` = :completed,`modified` = :modified '
					.' where id = :id'
				);
				$stmt->bindParam(':id', $this->id);
				$stmt->bindParam(':name', $this->name);
				$stmt->bindParam(':is_complete', $this->is_complete ? 1 : 0);
				$stmt->bindParam(':completed', db_util::datetime_format($this->completed));
				$stmt->bindParam(':modified', db_util::datetime_format($this->modified));
				return $stmt->execute();
			} else {
				$this->created = time();
				$this->completed = 0;
				if ( $this->is_complete ) $this->completed = time();
				
				$stmt = app::$db->prepare(
					'insert into `task` (`name`,`is_complete`,`completed`,`created`,`modified`) values(:name,:is_complete,:completed,:created,:modified)'
				);
				$stmt->bindParam(':name', $this->name);
				$stmt->bindParam(':is_complete', $this->is_complete ? 1 : 0);
				$stmt->bindParam(':completed', db_util::datetime_format($this->completed));
				$stmt->bindParam(':created', db_util::datetime_format($this->created));
				$stmt->bindParam(':modified', db_util::datetime_format($this->modified));
				$ret = $stmt->execute();
				if ( $ret ) $this->id = app::$db->lastInsertId();
				return $ret;
			}
		}
		
		public function delete() {
			$stmt = app::$db->prepare('select * from task where id = :id');
			$stmt->bindParam(':id', $this->id);
			$ret = $stmt->execute();
			if ( $ret ) $this->id = null;
			return $ret;
		}
		
		
		public static function id($id) {
			$stmt = app::$db->prepare('select * from task where id = :id');
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'task');
			return $stmt->fetch();
		}
		public static function all() {
			$stmt = app::$db->query('select * from task order by created asc');
			$stmt->execute();
			$stmt->setFetchMode(PDO::FETCH_CLASS, 'task');
			return $stmt;
		}
		
	}
