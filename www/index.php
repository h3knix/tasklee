<!doctype html>
<html ng-app>
<head>
	<meta charset="utf-8">
	<title>TaskLee</title>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.3/angular.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,300,700">
	<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	
	<header>
		<img src="img/ninja.png" alt="">
		TaskLee
		<span class="credit">Michael Vandenberghe</span>
	</header>
	
    <section>
    	<div class="task">
    		<input type="checkbox" title="mark complete">
    		<label>A Task</label>
    		<span>
    			<a href="#" title="edit"><i class="fa fa-pencil"></i></a>
    			<a href="#" title="remove"><i class="fa fa-remove"></i></a>
    		</span>
    	</div>
    </section>
    <section>
    	<button type="button"><i class="fa fa-plus"></i> Add Task</button>
    </section>
    
</body>
</html>