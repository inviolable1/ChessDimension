'use strict';

//Create a chess piece

angular.module('Services')
    .factory('ChessPiecesServ', [
		'KineticServ',
		function(KineticServ){
            
			//return output from function
			return {
				addPieces: function(squareSize, positions, chessLayer, images){
				
					var squareSize = squareSize || 60;
					
					var square = {};
					var i={}; 
					var j={};
					var xcoord = {};
					var ycoord = squareSize * 8;
					
					var createPiece = function(x, y, image, piece, squareSize, hoverCallback, hoverStopCallback, dragCallback, dropCallback){
						var chesspiece = new KineticServ.Image({
							x: x,
							y: y,
							image: image,
							width: squareSize,
							height: squareSize,
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
						chessLayer.draw();
						document.body.style.cursor = "pointer";		
					};
					var dropCallback = function(event){
						//change cursor back
						//document.body.style.cursor = "default";
					};
					
					//for each row
					for(i=1; i<9; i++){
						xcoord = -squareSize;	
						ycoord -= squareSize;
						for (j=1; j<9; j++){
							xcoord += squareSize;
							var pieceId = positions[i-1][j-1];
							if(pieceId !== ''){
								var piece = createPiece(xcoord, ycoord, images[pieceId], pieceId, squareSize, hoverCallback, hoverStopCallback, dragCallback, dropCallback);
								chessLayer.add(piece);
							}
						}
					}
					
					return chessLayer;
					
				}
			};
		}
	]);