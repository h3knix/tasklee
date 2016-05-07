var app = angular.module('tasklee', []);

app.controller('tasks', function($scope, $http) {
	
	get_tasks();
	function get_tasks(){
		$http.get('task').success(function(data){
			//$scope.tasks = data;
			console.log(data);
		});
	};
	
	$scope.add_task = function (task) {
		$http.post('task/'+ task).success(function(data){
			get_tasks();
			//$scope.taskInput = "";
		});
	};
	
	$scope.delete_task = function (task) {
		if ( confirm('Are you sure to delete this task?') ) {
			$http.post('task/'+ task + '/delete').success(function(data){
				get_tasks();
			});
		}
	};
	
	$scope.toggle_complete = function(item, status, task) {
		if ( status == '2' ) { status='0'; }else{ status='2'; }
		$http.post('task/'+ item).success(function(data){
			get_tasks();
		});
	};
	
});
