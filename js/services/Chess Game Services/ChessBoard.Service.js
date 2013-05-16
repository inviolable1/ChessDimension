'use strict';

//Create the chessboard (64 squares) 

angular.module('Services')
    .factory('ChessBoardServ', [
		'KineticServ',
		function(KineticServ){
            
			//function to create board square
			var createBoardSquare = function(x, y, fill, type, boardcoord, squareSize){
				var square = new KineticServ.Rect({ 
					x: x,
					y: y,
					width: squareSize,
					height: squareSize,
					fill: fill,									
					type: type,
					name: boardcoord,
					identity: 'boardSquare',
					stroke: '',
					strokeWidth: 0
				});
				// square.on('mouseenter', function(){
					// var activeBox = this;
					// console.log(activeBox.attrs.name);
				// });
			
				return square;
			};
			
			//return output from function
			return {
				createChessBoard: function(squareSize,chessLayer){
				
					var squareSize = squareSize || 60;
					
					var square = {};
					var i={}; 
					var j={};
					var xcoord = {};
					var ycoord = squareSize * 8;
					
					//this runs 8 times for each row
					for(i=1; i<9; i++){

						xcoord = -squareSize;	
						ycoord -= squareSize;
						
						if(i % 2 !== 0){
							//odd row
							for (j=1; j<9; j++){
							
								xcoord += squareSize;
							
								if(j % 2 !== 0){
									//odd column
									square = createBoardSquare(xcoord,ycoord,'#638598','black',String.fromCharCode(96 + j) + '' + (i),squareSize);									
									chessLayer.add(square);						
								}else{
									//even column
									square = createBoardSquare(xcoord,ycoord,'#FFE6CE','white',String.fromCharCode(96 + j) + '' + (i),squareSize);
									chessLayer.add(square);
								}
							
							}
						}else{
							//even row
							for (j=1; j<9; j++){
							
								xcoord += squareSize;
								
								if(j % 2 !== 0){
									//odd column
									square = createBoardSquare(xcoord,ycoord,'#FFE6CE','white',String.fromCharCode(96 + j) + '' + (i),squareSize);
									chessLayer.add(square);
								}else{
									//even column
									square = createBoardSquare(xcoord,ycoord,'#638598','black',String.fromCharCode(96 + j) + '' + (i),squareSize);
									chessLayer.add(square);
								}
							}
						}
					}
					
					return chessLayer;
					
				},
				
				coordOfBoard: function(squareSize){
					var chessBoardxyCoord = [];
					var row = [];
					var xyTopLeft = [];
					var ycoord = squareSize * 8;
					
					for (var i=1; i<9; i++){
						var xcoord = -squareSize;
						ycoord -= squareSize;
						row = [];
						
						for (var j=1; j<9; j++){
							xcoord += squareSize;							
							xyTopLeft = [xcoord,ycoord];
							row.push(xyTopLeft);
						}
						
						chessBoardxyCoord.push(row);
					}
					
					return chessBoardxyCoord;
				}
			};
		}
	]);