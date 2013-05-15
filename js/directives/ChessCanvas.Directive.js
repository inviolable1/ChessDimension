'use strict';

//create circular hit area for the pieces
//stop the pieces from moving out of the board (binding)
//do not allow dragging if not your turn. also, all cursors should be pointer
//abstract things like board creation, board pieces etc. into services
//throttle* - only carry out actions every 250ms so this thing doesnt jam - http://remysharp.com/2010/07/21/throttling-function-calls/

//legal moves -> green, illegal move->red

//check: double click somehow links to item on next div?

angular.module('Directives')
	.directive('chessCanvasDir', [
		'UtilitiesServ',
		'KineticServ',
		'ChessBoardServ',
		'$timeout',
		function(UtilitiesServ, KineticServ, ChessBoardServ, $timeout){
			return {
				scope: true,
				link: function(scope, element, attributes){
				
					//chess pieces image sources
					var sources = {
						R: 'img/White_Rook.png',
						N: 'img/White_Knight.png',
						B: 'img/White_Bishop.png',
						Q: 'img/White_Queen.png',
						K: 'img/White_King.png',
						P: 'img/White_Pawn.png',
						r: 'img/Black_Rook.png',
						n: 'img/Black_Knight.png',
						b: 'img/Black_Bishop.png',
						q: 'img/Black_Queen.png',
						k: 'img/Black_King.png',
						p: 'img/Black_Pawn.png'
					};		

					UtilitiesServ.canvasPreloadImages(sources, function(images){
				
						//activeBox where you are hovering over
						var activeBox = {};		
					
						var stage = new KineticServ.Stage({
							container: element[0],
							width: 480,
							height: 480
						});
						
                        const squareSize = 60;  //check if this is global and works in service or NOT
                        
                        //Create the chessboard (64 squares) 
						var chessBoardLayer = ChessBoardServ;   //try doing ChessBoardServ(squareSize)
						stage.add(chessBoardLayer); //later remove this, we only want to add the chessboard layer after all the board and pieces are done. this is only here now so we can check it works
                        
                        
						/*------------------------------------------------------------------
						Create the chess pieces
						-------------------------------------------------------------------*/
						//Position of pieces on chess board
						// var positions = [
							// ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
							// ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
							// ['',   '',  '',  '',  '',  '',  '',  ''],
							// ['',   '',  '',  '',  '',  '',  '',  ''],
							// ['',   '',  '',  '',  '',  '',  '',  ''],
							// ['',   '',  '',  '',  '',  '',  '',  ''],
							// ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
							// ['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r']
						// ];

						//or enter in any other desired starting position. Note: White is on top (yuck)
						var positions = [
							['',   '',  '',  '',  '',  '',  '',  'R'],
							['',   '',  'P',  '',  '',  'P',  'K',  ''],
							['',   'B',  '',  '',  'Q',  'N',  'P',  ''],
							['',   'P',  '',  '',  'P',  '',  '',  ''],
							['R',   '',  'B',  'N',  'p',  '',  'n',  ''],
							['p',   '',  'b',  '',  'n',  '',  'p',  'p'],
							['',   'q',  'p',  '',  '',  'p',  'b',  ''],
							['r',   '',  '',  '',  'r',  '',  'k',  '']
						];

                        //refer to ChessPiecesServ here			
                        
                            //need some kind of var piece = chessPiecesServ(chessBoardLayer) try and inject it in, so we can just put all this for loop in the serv.
						ycoord = 480;
						
						//for each row
						for(i=1; i<9; i++){
							xcoord = -60;	
							ycoord -= 60;
							for (j=1; j<9; j++){
								xcoord += 60;
								var pieceId = positions[i-1][j-1];
								if(pieceId !== ''){
									var piece = createPiece(xcoord, ycoord, images[pieceId], pieceId, hoverCallback, hoverStopCallback, dragCallback, dropCallback);
									chessBoardLayer.add(piece);
								}
							}
						}

						//Add chessBoardLayer to stage
						stage.add(chessBoardLayer);
						
						/*------------------------------------------------------------------
						Find where piece is currently hovered over
						-------------------------------------------------------------------*/
						//board coordinates (chess notation) for chess squares
						var chessBoardCoord = [
							['a1', 'b1', 'c1', 'd1', 'e1', 'f1', 'g1', 'h1'],	
							['a2', 'b2', 'c2', 'd2', 'e2', 'f2', 'g2', 'h2'],	
							['a3', 'b3', 'c3', 'd3', 'e3', 'f3', 'g3', 'h3'],	
							['a4', 'b4', 'c4', 'd4', 'e4', 'f4', 'g4', 'h4'],	
							['a5', 'b5', 'c5', 'd5', 'e5', 'f5', 'g5', 'h5'],	
							['a6', 'b6', 'c6', 'd6', 'e6', 'f6', 'g6', 'h6'],	
							['a7', 'b7', 'c7', 'd7', 'e7', 'f7', 'g7', 'h7'],	
							['a8', 'b8', 'c8', 'd8', 'e8', 'f8', 'g8', 'h8'],								
						];										
						
						//xy coordinates for chess squares (top left hand corner of square)
							//this creates an array like the one above, but with coordinates for top left
						var squareSize = 60;
						var marginsChessBoard = [0,0]; //top, left 
						
						var chessBoardxyCoord = [];
						var row = [];
						var xyTopLeft = [];
						ycoord = squareSize * 8 + marginsChessBoard[0];
						
						for (i=1; i<9; i++){
							xcoord = -squareSize + marginsChessBoard[1];
							ycoord -= squareSize;
							row = [];
							
							for (j=1; j<9; j++){
								xcoord += squareSize;							
								xyTopLeft = [xcoord,ycoord];
								row.push(xyTopLeft);
							}
							
							chessBoardxyCoord.push(row);
						}						
						
						//mouse position
						var mousePosition = {};
						var activeCell = {};	//a1 or b3
						var topLeftActiveCell = {};
						var activePiece = {};
						
						var chessBoardSquare = {};
						var oldBoardSquare = {};
						
						var destinationPiece = {};
						
						chessBoardLayer.on('mousedown dragmove'	,function(event){	
							//need to add functionally to highlight squares when u're hover over it
							
							activePiece = event.targetNode;
							activePiece.moveUp();
							mousePosition = stage.getPointerPosition();
							
							//console.log(mousePosition);
						
							// go through the board to find out which row and column the current mouse position is in
								// return board coordinate (chess notation) and xycoord for the top left corner of the square
								// highlight the cell (temporary border?)
							for (i=0; i<8; i++){
								for (j=0; j<8; j++){
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

							getChessBoardSquares(activeCell);
							
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
						
						chessBoardLayer.on('mouseup dragend', function(evt){
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
							getDestinationPiece(topLeftActiveCell);	//get the piece
							if(!isEmpty(destinationPiece)){
								destinationPiece.remove();			//remove piece on destination square (if any)
							}							
							chessBoardLayer.draw();					//redraw layer
						});
						
						//function to match chess board squares to active cell
						var getChessBoardSquares = function(activeCell){						
							for (i=0; i<chessBoardLayer.children.length; i++){
								if(chessBoardLayer.children[i].attrs.name === activeCell){
									chessBoardSquare = chessBoardLayer.children[i];
									//console.log(chessBoardSquare);
								}							
							}
							if (isEmpty(oldBoardSquare)){
								oldBoardSquare = chessBoardSquare;
							}

						};
									
						//function to give any other piece on destination square (other than your current one)
						var getDestinationPiece = function(topLeftActiveCell){
							for (i=0; i<chessBoardLayer.children.length; i++){
								if(	chessBoardLayer.children[i].attrs.x === topLeftActiveCell[0] && 
									chessBoardLayer.children[i].attrs.y === topLeftActiveCell[1] &&
									chessBoardLayer.children[i].attrs.identity === 'piece' &&
									chessBoardLayer.children[i] !== activePiece
								){
									destinationPiece = chessBoardLayer.children[i];
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
						
						// console.log(chessBoardLayer.children);
					});				
				}
			};
		}
	]);