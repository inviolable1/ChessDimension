'use strict';

angular.module('Services')
	.factory('ChessGlobalVarsServ', [
		function(){
		
			//Initiation Variables
			var InitiationVars = {
				squareSize: 60
			}			
				
			return InitiationVars;
		
		}
	]);