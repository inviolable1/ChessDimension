'use strict';

angular.module('Directives')
	.directive('chessCanvasDir', [
		function(){
			return {
				scope: true,
				link: function(scope, element, attributes){
				
					var canvas = element[0];	//the actual canvas DOM element
					console.log(canvas);
					var context = canvas.getContext('2d');
					
					context.beginPath();
					context.moveTo(200, canvas.height / 2 - 50);
					context.lineTo(canvas.width - 200, canvas.height / 3);
					context.lineWidth = 15;
					context.strokeStyle = '#ff0000';
					context.lineCap = 'round';
					context.stroke();
					
					context.beginPath();
					context.arc(230, 140, 75, 0.1 * Math.PI, 1.4 * Math.PI, false);	//1st 2 parameters are context points, then radius, starting angle, ending angle, clockwise(false) or anticlockwise(true)
					context.lineWidth = 15;
					context.strokeStyle = 'black';
					context.stroke();
					
					context.beginPath();
					context.moveTo(canvas.width - 200, canvas.height/3);
					context.lineTo(220,150);
					context.lineWidth = 20;
					// context.strokeStyle = 'purple';
					// context.lineCap = 'round';
					context.stroke();
				
				}
			};
		}
	]);