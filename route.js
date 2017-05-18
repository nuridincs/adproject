		// create the module and name it app
	var app = angular.module('main-adaprojek', [
		'ngRoute',
		'ui.bootstrap.modal',
    	'ui.bootstrap.tpls'
	]);

	var base_url = "http://localhost:8080/development/adaprojek/services/index.php/";
	var srv_url = "http://localhost:8080/development/adaprojek/services/app-services/";
	//var $httpDefaultCache = $cacheFactory.get('$http');

	var checkSession = function(){
		if(localStorage.length > 0){
			return "/home";
		} else {
			return "/login";
		}
	}

	// configure our routes
	app.config(function($routeProvider) {
		$routeProvider

			// route for the home page
			.when('/', {
				cache: false,
				templateUrl : 'pages/home.html',
				controller  : 'mainController',
			})

			// route for the about page
			.when('/about', {
				templateUrl : 'pages/about.html',
				controller  : 'aboutController',
				cache: false
			})

			// route for the contact page
			.when('/contact', {
				templateUrl : 'pages/contact.html',
				controller  : 'contactController',
				cache: false
			})

			.when('/bid', {
				templateUrl : 'pages/form_bid.html',
				controller  : 'mainController',
				cache: false
			})

			.when('/register', {
				templateUrl : 'pages/register.html',
				controller  : 'registerController',
				cache: false
			})

			.when('/project', {
				templateUrl : 'pages/list_project.html',//'pages/projectForm.html',
				controller  : 'projectController',
				cache: false
			})

			.when('/addproject', {
				templateUrl : 'pages/projectForm.html',//'pages/projectForm.html',
				controller  : 'addprojectController',
				cache: false
			})

			.when('/activate', {
				templateUrl : 'pages/activate.html',//'pages/list_project.html',
				controller  : 'activateController',
				cache: false
			})

			.when('/company', {
				templateUrl : 'pages/list_company.html',//'pages/companyForm.html',
				controller  : 'companyController',
				cache: false
			})

			.when('/addcompany', {
				templateUrl : 'pages/companyForm.html',//'pages/companyForm.html',
				controller  : 'addcompanyController',
				cache: false
			})

			.when('/detailProject', {
				templateUrl : 'pages/detailProject.html',
				controller  : 'mainController',
				cache: false
			})

			.when('/login', {
				templateUrl : 'pages/login.html',
				controller  : 'loginController',
				cache: false
			});

			/*.otherwise({
                checkSession();
            });*/
	});

	/*app.run(function($rootScope, $templateCache) {
	   $rootScope.$on('$viewContentLoaded', function() {
	      $templateCache.removeAll();
	   });
	});*/

	/*app.config(function($routeProvider, $locationProvider) {
	  $routeProvider.otherwise({redirectTo: '/home'});
	  $locationProvider.html5Mode(true).hashPrefix('!');
	});*/

	app.run(function($rootScope, $templateCache) {
	    $rootScope.$on('$routeChangeStart', function(event, next, current) {
	        if (typeof(current) !== 'undefined'){
	            $templateCache.remove(current.templateUrl);
	        }
	    });
	});



	/*app.run(function($rootScope){
		$rootScope.local = localStorage.getItem('LOGGED');
	})*/

	/*// create the controller and inject Angular's $scope
	app.controller('mainController', function($scope) {
		// create a message to display in our view
		$scope.message = 'Everyone come and see how good I look!';
	});

	app.controller('aboutController', function($scope) {
		$scope.message = 'Look! I am an about page.';
	});

	app.controller('contactController', function($scope) {
		$scope.message = 'Contact us! JK. This is just a demo.';
	});*/