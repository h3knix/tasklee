var app = angular.module('taskleeApp', ['ngMaterial']);

app.controller('tasksController', function($scope, $http, $mdDialog) {
	$scope.muted = false;
	$scope.has_audio = false;
	
	var audio_controller = {
		"audios": {}
		,"play": function(sound_name) {
			if ( ! $scope.muted && sound_name in audio_controller.audios ) {
				audio_controller.audios[sound_name].play();
			}
		}	
	};
	if ( Modernizr.audio ) {
		$scope.has_audio = true;
		audio_controller.audios = {
			"complete": new Audio('media/slap.mp3')
			,"delete": new Audio('media/snap.mp3')
			,"new": new Audio('media/throw.mp3')
		};
	}
	
	get_tasks();
	
	function get_tasks(){
		$http.get('task').then(function(response){
			$scope.tasks = response.data;
		},handle_response_error);
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
	function handle_response_error(response) {
		if ( "data" in response && response.data && "errors" in response.data ) {
			show_alert(response.data.errors.join(', '));
		} else {
			show_alert('An error happend');
		}
		console.log(response);
	}
	
	$scope.add_task = function() {
		$http.post('task/',{"name": $scope.taskNewName}).then(function(response){
			audio_controller.play('new');
			get_tasks();
			$scope.taskNewName = '';
		},handle_response_error);
	};
	
	$scope.delete_task = function(id) {
		show_yesno('Are you sure to delete this task?',function(){
			$http.delete('task/'+ id).then(function(data){
				audio_controller.play('delete');
				get_tasks();
			},handle_response_error);
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
				audio_controller.play('complete');
			} else {
				audio_controller.play('new');
			}
			get_tasks();
		},handle_response_error);
	};
	
});
