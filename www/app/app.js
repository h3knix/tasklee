var app = angular.module('taskleeApp', ['ngMaterial']);

var complete_audio = new Audio('media/slap.mp3');
var delete_audio = new Audio('media/snap.mp3');
var new_audio = new Audio('media/throw.mp3');

app.controller('tasksController', function($scope, $http, $mdDialog) {
	
	get_tasks();
	
	function get_tasks(){
		$http.get('task').then(function(response){
			$scope.tasks = response.data;
			console.log(response);
		},function(response){
			show_alert('An error happend');
			console.log(data);
		});
	};
	function show_alert(text) {
		$mdDialog.show(
			$mdDialog.alert()
			.parent(angular.element(document.querySelector('#popupContainer')))
			.clickOutsideToClose(true)
			.title('Hey!')
			.textContent(text)
			.ok('Ok')
		);
	}
	function show_yesno(text,after_confirm) {
		$mdDialog.show(
			$mdDialog.confirm()
			.title('Sure?')
			.textContent(text)
			.ok('Yes')
			.cancel('No')
		).then(after_confirm, function(){});
	}
	
	$scope.add_task = function() {
		$http.post('task/',{"name": $scope.taskNewName}).then(function(response){
			new_audio.play();
			get_tasks();
			$scope.taskNewName = "";
		},function(response){
			if ( response.data && "errors" in response.data ) {
				show_alert(response.data.errors.join(', '));
			} else {
				show_alert('An error happend');
			}
			console.log(response);
		});
	};
	
	$scope.delete_task = function(id) {
		show_yesno('Are you sure to delete this task?',function(){
			$http.delete('task/'+ id).then(function(data){
				delete_audio.play();
				get_tasks();
			},function(response){
				show_alert('An error happend');
				console.log(response);
			});
		});
	};
	
	$scope.toggle_complete = function(id, is_complete) {
		if ( is_complete == '1' ) {
			is_complete = 0;
		} else {
			is_complete = 1;
		}
		$http.post('task/'+ id,{ "is_complete": is_complete }).then(function(response){
			if ( is_complete ) {
				complete_audio.play();
			} else {
				new_audio.play();
			}
			get_tasks();
		},function(response){
			show_alert('An error happend');
			console.log(response);
		});
	};
	
});
