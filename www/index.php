<?php
	
	//shouldnt have to change this config param
	define('ROOT',dirname(dirname(__FILE__)) .'/');
	
	spl_autoload_register(function($name) {
		$p = ROOT . 'lib/'. basename($name) .'.php';
		if ( file_exists($p) ) include $p;
		else throw new Exception('could not load class '. $name);
	});
	
	//setup conf with default values
	app::$conf = new conf();
	
	
	//get the route path
	$route = util_http::route_path(app::$conf->web_root);
	
	
	//get the controller and do some rudimentary routing
	$controller = array_shift($route);
	if ( $controller ) {
		//dont let browser cache dynamic stuff
		util_http::set_header_nocache();
		
		//api is going to return json everytime
		header('Content-Type: application/json');
		
		//hold our return payload
		$json_ret = new json_return();
		
		try {
			
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
							util_http::set_header_notfound_error();
							$json_ret->add_error('invalid task id');
						} else {
							switch(util_http::request_method()) {
								case 'delete':
									if ( ! $task->delete() ) {
										util_http::set_header_server_error();
										$json_ret->add_error('failed to delete');
									}
								break;
								case 'post':
									$payload = util_http::json_input_payload();
									if ( $payload ) {
										if ( isset($payload->is_complete) ) {
											$task->is_complete = ( $payload->is_complete ? 1 : 0 );
											if ( ! $task->save() ) {
												util_http::set_header_server_error();
												$json_ret->add_error('failed to save');
											}
										}
									} else {
										util_http::set_header_bad_request();
										$json_ret->add_error('invalid input format');
									}
								break;
								default:
									$json_ret->data = $task;
								break;
							}
						}
					} else {
						switch(util_http::request_method()) {
							//if no task id and posting, then we are creating a new task
							case 'post':
								$payload = util_http::json_input_payload();
								if ( $payload ) {
									if ( isset($payload->name) && trim($payload->name) != '' ) {
										$task = new task();
										$task->name = $payload->name;
										if ( ! $task->save() ) {
											util_http::set_header_server_error();
											$json_ret->add_error('failed to save');
										}
									} else {
										util_http::set_header_bad_request();
										$json_ret->add_error('task name is required');
									}
								} else {
									util_http::set_header_bad_request();
									$json_ret->add_error('invalid input format');
								}
							break;
							default:
								$task_set = task::all();
								$json_ret->data = $task_set->fetchAll();
							break;
						}
						
					}
				break;
			}
		} catch(Exception $e) {
			//some bad stuff happend
			util_http::set_header_server_error();
			
			//in a production environment, we wouldn't show the exception message to the user
			//we'd log it and tell them something else, but I wanted this here for quick debug
			$json_ret->add_error('Exception: '. $e->getMessage());
		}
		
		$json_ret->output();
	} else {
		include ROOT . 'www/app/views/app.html';
	}
