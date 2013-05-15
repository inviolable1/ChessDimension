'use strict';

//Create a chess piece

angular.module('Services')
    .factory('ChessPiecesServ', [
		function(){
            
    		var createPiece = function(x, y, image, piece, hoverCallback, hoverStopCallback, dragCallback, dropCallback){
    			var chesspiece = new KineticServ.Image({
    				x: x,
    				y: y,
    				image: image,
    				width: 60,
    				height: 60,
    				name: piece,
    				identity: 'piece',
    				draggable: true,
    			});
    			
    			chesspiece.on('mouseover', hoverCallback);
    			chesspiece.on('mouseout', hoverStopCallback);
    			chesspiece.on('dragmove', dragCallback);	//mousedown
    			chesspiece.on('dragend', dropCallback);	//mouseout
    			
    			return chesspiece;
    			
    		};
    		
    		//disables text cursor (problem with this is that it disables totally. can i make this only apply for canvas?)
    		document.onselectstart = function(){ return false; };
    		
    		var hoverCallback = function(event){
    			//make mouse cursor a pointer
    			document.body.style.cursor = "pointer";
    		};
    		var hoverStopCallback = function(event){
    			//change cursor back
    			document.body.style.cursor = "default";
    		};
    		var dragCallback = function(event){
    
    			//bring piece to the front
    			this.moveUp();
    			chessBoardLayer.draw();
    			document.body.style.cursor = "pointer";		
    		};
    		var dropCallback = function(event){
    			//change cursor back
    			//document.body.style.cursor = "default";
    		};
		}
	]);