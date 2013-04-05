'use strict';

angular.module('Controllers')
	.controller('LoginIndexCtrl', [
		'$scope',
		'SessionsServ',
		function($scope){
			
			//get a service to the sessions resource
			//log the details in!
			
			$scope.submitLogin = function(){
			
				console.log(this);
				var payload = {
					username: this.loginForm.username,
					password: this.loginForm.password,
					rememberMe: this.loginForm.rememberMe
				};
				
				//now send the payload via the service (dependency inject)

				SessionsServe.save(
					{},
					payload,
					function(successResponse){
						console.log(successResponse, 'Send the payload');
					},
					function(failureResponse){
						console.log(failureResponse, 'Failed the login');
					}
				
			};
			
		}
	]);