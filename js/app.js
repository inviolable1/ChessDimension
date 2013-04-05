'use strict';

/* ==========================================================================
   BOOTSTRAPPER
   ========================================================================== */

//app is an module that is dependent on several top level modules
//modules just act as namespaces
var app = angular.module('App', [
	'Controllers',
	'Filters',
	'Services',
	'Directives',
	'ngResource', //for RESTful resources
	'ngCookies'
]);

//Define all the page level controllers (Application Logic)
angular.module('Controllers', []);
//Define all shared filters (UI Filtering)
angular.module('Filters', []);
//Define all shared services (Interaction with Backend)
angular.module('Services', []);
//Define all shared directives (UI Logic)
angular.module('Directives', []);

/* ==========================================================================
   ROUTER
   ========================================================================== */

   //NEED TO CHANGE THIS TO BE MY OWN, NOT POLYCADEMY!*************
   
//Define all routes here and which page level controller should handle them
app.config(
	[
		'$routeProvider',
		'$locationProvider',
		function($routeProvider, $locationProvider) {
			
			//HTML5 Mode URLs
			$locationProvider.html5Mode(true).hashPrefix('!');
			
			//Routing	(TOP level page states, e.g. HOME,PLAY,HELP pages)
			$routeProvider
				.when(
					'/',
					{
						templateUrl: 'home_index.html',
						controller: 'HomeIndexCtrl'
					}
				)
				.when(
					'/dummy',
					{
						templateUrl: 'dummy_index.html',
						controller: 'DummyIndexCtrl',
					}
				)
				.when(
					'/courses',
					{
						templateUrl: 'courses_index.html',
						controller: 'CoursesIndexCtrl'
					}
				)
				.when(
					'/blog',
					{
						templateUrl: 'blog_index.html',
						controller: 'BlogIndexCtrl'
					}
				)
				.when(
					'/nested',
					{
						templateUrl: 'nested_index.html',
						controller: 'NestedIndexCtrl'
					}
				)
				.otherwise(
					{
						redirectTo: '/'
					}
				);
			
		}
	]
);

/* ==========================================================================
   GLOBAL FEATURES
   ========================================================================== */

app.run([
	'$rootScope',
	'$cookies',
	'$http',
	function($rootScope, $cookies, $http){
	
		//XSRF INTEGRATION
		$rootScope.$watch(	//$ sign means this is native AngularJS variable, DO NOT overwrite!
			function(){
				return $cookies[serverVars.csrfCookieName];	//serverVars defined in Footer partial
			},
			function(){
				$http.defaults.headers.common['X-XSRF-TOKEN'] = $cookies[serverVars.csrfCookieName];
			}
		);
		
		//XHR ERROR HANDLER
		
	}
]);