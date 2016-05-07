<?php
	
	//some prelim config
	define('WEB_ROOT','/tasklee/');
	define('DB_DSN','mysql:host=localhost;dbname=tasklee;charset=utf8');
	define('DB_USER','tasklee');
	define('DB_PASS','');
	
	//shouldnt have to change this config param
	define('ROOT',dirname(dirname(__FILE__)) .'/');
	
	spl_autoload_register(function($name) {
		$p = ROOT . 'lib/'. basename($name) .'.php';
		if ( file_exists($p) ) include $p;
		else throw new Exception('could not load class '. $name);
	});
	
	
	//get the route path and request method
	$route = array();
	preg_match('/^'. preg_quote(WEB_ROOT,'/') .'([^\?]*)/',$_SERVER['REQUEST_URI'],$route);
	if ( is_array($route) && isset($route[1]) ) {
		//just in case, get rid of whitespace before and after trimming forwardslash
		$route = trim(trim(trim($route[1]),'/'));
		$route = explode('/',$route);
	} else $route = array();
	
	$request_method = '';
	if ( isset($_POST['_method']) ) $request_method = $_POST['_method'];
	else if ( isset($_SERVER['REQUEST_METHOD']) ) $request_method = $_SERVER['REQUEST_METHOD'];
	$request_method = strtolower($request_method);
	
	
	
	//get the controller and do some rudimentary routing
	$controller = array_shift($route);
	if ( $controller ) {
		//dont let browser cache dynamic stuff
		header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		header('Pragma: no-cache');
		
		//api is going to return json everytime
		header('Content-Type: application/json');
		
		//hold our return payload
		$json_ret = array();
		
		try {
			function set_header_code($code,$str) {
				header($_SERVER['SERVER_PROTOCOL'] .' '. $str, true, $code);
			}
			function set_header_server_error() {
				set_header_code(500,'Internal Server Error');
			}
			
			//lets database
			app::db_connect();
			
			switch($controller) {
				case 'task':
					//task id comes next in our url path
					$task_id = array_shift($route);
					
					//if we are operating on a specific task
					if ( $task_id ) {
						$task = task::id($task_id);
						if ( ! $task ) {
							set_header_code(404,'Not Found');
							$json_ret = array(
								'errors' => array(
									'invalid task id',
								),
							);
						} else {
							switch($request_method) {
								case 'delete':
									if ( ! $task->delete() ) {
										set_header_server_error();
										$json_ret = array(
											'errors' => array(
												'failed to delete',
											),
										);
									}
								break;
								case 'post':
									$json_ret[] = 'edit task '. $task_id;
								break;
								default:
									$json_ret = task::id($task_id);
								break;
							}
						}
					} else {//otherwise this is a task list
						switch($request_method) {
							case 'post'://if no task id and posting, then we are creating a new task
								$json_ret[] = 'create task';
							break;
							default:
								$task_set = task::all();
								$json_ret = $task_set->fetchAll();
							break;
						}
						
					}
				break;
			}
		} catch(Exception $e) {
			//some bad stuff happend
			set_header_server_error();
			
			//in a full production environment, we wouldn't show the exception message to the user
			//we'd log it and tell them something else, but I wanted this here for quick debug
			$json_ret = array(
				'errors' => array(
					'Exception: '. $e->getMessage(),
				),
			);
		}
		
		echo json_encode($json_ret);
	} else {
		include ROOT . 'www/app/views/app.html';
	}
