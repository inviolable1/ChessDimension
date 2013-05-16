'use strict';

//Actions on the Chess Layer 
//this will inject the validation service to check the validity of moves

angular.module('Services')
    .factory('ChessLayerServ', [
		'KineticServ',
		function(KineticServ){
			
			//mouse position
			var mousePosition = {};
			var activeCell = {};	//a1 or b3
			var topLeftActiveCell = {};
			var activePiece = {};
			
			var chessBoardSquare = {};
			var oldBoardSquare = {};
			
			var destinationPiece = {};
			
			//function to match chess board squares to active cell
			var getChessBoardSquares = function(activeCell, chessLayer){						
				for (var i=0; i<chessLayer.children.length; i++){
					if(chessLayer.children[i].attrs.name === activeCell){
						chessBoardSquare = chessLayer.children[i];
						//console.log(chessBoardSquare);
					}							
				}
				if (isEmpty(oldBoardSquare)){
					oldBoardSquare = chessBoardSquare;
				}

			};
						
			//function to give any other piece on destination square (other than your current one)
			var getDestinationPiece = function(chessLayer, topLeftActiveCell){
				for (var i=0; i<chessLayer.children.length; i++){
					if(	chessLayer.children[i].attrs.x === topLeftActiveCell[0] && 
						chessLayer.children[i].attrs.y === topLeftActiveCell[1] &&
						chessLayer.children[i].attrs.identity === 'piece' &&
						chessLayer.children[i] !== activePiece
					){
						destinationPiece = chessLayer.children[i];
					}
				}
			};
			
			//generic function that checks if array is empty
			function isEmpty(map) {
				for(var key in map) {
					if (map.hasOwnProperty(key)) {
						return false;
					}
				}
				return true;
			}			

			//return output from function
			return {
				mouseDownAction: function(chessLayer, stage, chessBoardxyCoord, squareSize, chessBoardCoord){
					chessLayer.on('mousedown dragmove'	,function(event){	
						
						activePiece = event.targetNode;
						activePiece.moveUp();
						mousePosition = stage.getPointerPosition();
						
						//console.log(mousePosition);
					
						// go through the board to find out which row and column the current mouse position is in
							// return board coordinate (chess notation) and xycoord for the top left corner of the square
						for (var i=0; i<8; i++){
							for (var j=0; j<8; j++){
								if (mousePosition.x > chessBoardxyCoord[i][j][0] &&
									mousePosition.x <= chessBoardxyCoord[i][j][0] + squareSize &&
									mousePosition.y > chessBoardxyCoord[i][j][1] &&
									mousePosition.y <= chessBoardxyCoord[i][j][1] + squareSize							
								){
									activeCell = chessBoardCoord[i][j];
									topLeftActiveCell = chessBoardxyCoord[i][j];
									//console.log(topLeftActiveCell);
								}
							}
						}

						getChessBoardSquares(activeCell, chessLayer);
						
						//check if chessBoardSquare is still the active cell
						if(oldBoardSquare.attrs.name !== activeCell) {
							//highlight chessBoardSquare (which changes when we getChessBoardSquares)
							
							// oldBoardSquare.setStroke('');
							// oldBoardSquare.setStrokeWidth(0);
							if(oldBoardSquare.attrs.type === 'white'){
								oldBoardSquare.setFill('#FFE6CE');
							}else{
								oldBoardSquare.setFill('#638598');
							}
							
							// chessBoardSquare.setStroke('red');
							// chessBoardSquare.setStrokeWidth(5);
							chessBoardSquare.setFill('#CA9FB7');	//PINK! highlighted
							
							oldBoardSquare = chessBoardSquare;
						}else{
							chessBoardSquare.setFill('#CA9FB7');
						}

					});
					//note: functions DON'T have to return things, can just manipulate things
				},
				
				mouseUpAction: function(chessLayer){
					chessLayer.on('mouseup dragend', function(evt){
						//snap to top left of the square you're clicking in
						activePiece.setPosition(topLeftActiveCell[0],topLeftActiveCell[1]);

						//remove highlighting of cell
						// chessBoardSquare.setStroke('');
						// chessBoardSquare.setStrokeWidth(0);
						if(chessBoardSquare.attrs.type === 'white'){
							chessBoardSquare.setFill('#FFE6CE');
						}else{
							chessBoardSquare.setFill('#638598');
						}

						//if there is something other than activePiece on the destination square remove it
						getDestinationPiece(chessLayer, topLeftActiveCell);	//get the piece
						if(!isEmpty(destinationPiece)){
							destinationPiece.remove();			//remove piece on destination square (if any)
						}							
						chessLayer.draw();					//redraw layer
					});
				}
			};
		}
	]);