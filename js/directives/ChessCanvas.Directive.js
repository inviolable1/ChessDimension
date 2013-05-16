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
		'ChessPiecesServ',
		'ChessLayerServ',
		'$timeout',
		function(UtilitiesServ, KineticServ, ChessBoardServ, ChessPiecesServ, ChessLayerServ, $timeout){
			return {
				scope: true,
				link: function(scope, element, attributes){
				
					var squareSize = 60;
				
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
				
						var stage = new KineticServ.Stage({
							container: element[0],
							width: squareSize * 8,
							height: squareSize * 8
						});
						
						var chessLayer = new KineticServ.Layer();
						
                        //Create the chessboard (64 squares) 
						chessLayer = ChessBoardServ.createChessBoard(squareSize, chessLayer);
                        
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

                        //Add chess pieces to chess board layer
						chessLayer = ChessPiecesServ.addPieces(squareSize, positions, chessLayer, images);
						
						stage.add(chessLayer); 
                        console.log(chessLayer);
						
						/*------------------------------------------------------------------
						Actions on mouse dragging/dropping
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
						var chessBoardxyCoord = ChessBoardServ.coordOfBoard(squareSize);
						
						//Various Mouse Actions
						ChessLayerServ.mouseDownAction(chessLayer, stage, chessBoardxyCoord, squareSize, chessBoardCoord);
						ChessLayerServ.mouseUpAction(chessLayer);	//include validation of moves
						
					});				
				}
			};
		}
	]);