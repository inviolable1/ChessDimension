'use strict';

//create circular hit area for the pieces
//stop the pieces from moving out of the board (binding)
//do not allow dragging if not your turn. also, all cursors should be pointer
//delegation of events over multiple layers

angular.module('Directives')
	.directive('chessCanvasDir', [
		'UtilitiesServ',
		'KineticServ',
		'$timeout',
		function(UtilitiesServ, KineticServ, $timeout){
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
				
						//activeBox where you are hovering piece over
						var activeBox = {};		
					
						var stage = new KineticServ.Stage({
							container: element[0],
							width: 480,
							height: 480
						});

						var chessBoardLayer = new KineticServ.Layer();
						var chessPiecesLayer = new KineticServ.Layer();					
						
						/*------------------------------------------------------------------
						Create the chessboard (64 squares) 
						-------------------------------------------------------------------*/
						
						//function to create board square
						var createBoardSquare = function(x, y, fill, type, boardcoord){
							var square = new KineticServ.Rect({ 
								x: x,
								y: y,
								width: 60,
								height: 60,
								fill: fill,									
								type: type,
								boardcoord: boardcoord
							});
							square.on('mouseenter', function(){
								activeBox = this;
								console.log(activeBox.attrs.boardcoord);
							});
						
							return square;
						};
						
						var square = {};
						var i={}; 
						var j={};
						var ycoord = 480;
						
						//this runs 8 times for each row
						for(i=1; i<9; i++){

							var xcoord = -60;	
							ycoord -= 60;
							
							if(i % 2 !== 0){
								//odd row
								for (j=1; j<9; j++){
								
									xcoord += 60;
								
									if(j % 2 !== 0){
										//odd column
										square = createBoardSquare(xcoord,ycoord,'#638598','black',String.fromCharCode(96 + j) + '' + (i));
										
										chessBoardLayer.add(square);

										
									}else{
										//even column
										square = createBoardSquare(xcoord,ycoord,'#FFE6CE','white',String.fromCharCode(96 + j) + '' + (i));
				
										chessBoardLayer.add(square);
									}
								
								}
							}else{
								//even row
								for (j=1; j<9; j++){
								
									xcoord += 60;
									
									if(j % 2 !== 0){
										//odd column
										square = createBoardSquare(xcoord,ycoord,'#FFE6CE','white',String.fromCharCode(96 + j) + '' + (i));
									
										chessBoardLayer.add(square);
									}else{
										//even column
										square = createBoardSquare(xcoord,ycoord,'#638598','black',String.fromCharCode(96 + j) + '' + (i));

										chessBoardLayer.add(square);
									}
								
								}
							}
							
						}
						
						//Add chessBoardLayer to stage
						stage.add(chessBoardLayer);

						/*------------------------------------------------------------------
						Create the chess pieces
						-------------------------------------------------------------------*/
						//Position of pieces on chess board
						var positions = [
							['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'],
							['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
							['',   '',  '',  '',  '',  '',  '',  ''],
							['',   '',  '',  '',  '',  '',  '',  ''],
							['',   '',  '',  '',  '',  '',  '',  ''],
							['',   '',  '',  '',  '',  '',  '',  ''],
							['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
							['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r']
						];
						
						var createPiece = function(x, y, image, piece, hoverCallback, hoverStopCallback, dragCallback, dropCallback){
							var piece = new KineticServ.Image({
								x: x,
								y: y,
								image: image,
								width: 60,
								height: 60,
								piece: piece,
								draggable: true,
								dragBoundFunc: function(pos) {
									var newX = pos.x > 480 ? 480 : pos.x;
									var newY = pos.y > 480 ? 480 : pos.y;
									return {
										x: newX,
										y: newY
									};
								}
							});
							
							piece.on('mouseover', hoverCallback);
							piece.on('mouseout', hoverStopCallback);
							piece.on('dragmove', dragCallback);
							piece.on('dragend', dropCallback);
							
							return piece;
							
						}
						
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
							chessPiecesLayer.draw();
							document.body.style.cursor = "pointer";
							
						}
						var dropCallback = function(event){
							//get active box, and place it on the active box!
							
							//change cursor back
							document.body.style.cursor = "default";
						}
						
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
									chessPiecesLayer.add(piece);
								}
							}
						}
						stage.add(chessPiecesLayer);
					});				
				}
			};
		}
	]);