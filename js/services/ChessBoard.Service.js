'use strict';

//Create the chessboard (64 squares) 

angular.module('Services')
    .factory('ChessBoardServ', [
		'KineticServ',
		function(KineticServ){

			var chessBoardLayer = new KineticServ.Layer();
		
			//put this outside, and include them as parameters into the function
			var square = {};
			var i={}; 
			var j={};
			var ycoord = 480;
			var xcoord = -60;
			
			//this runs 8 times for each row
			for(i=1; i<9; i++){

				xcoord = -60;	
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
			
			//function to create board square
			var createBoardSquare = function(x, y, fill, type, boardcoord){
				var square = new KineticServ.Rect({ 
					x: x,
					y: y,
					width: 60,
					height: 60,
					fill: fill,									
					type: type,
					name: boardcoord,
					identity: 'boardSquare',
					stroke: '',
					strokeWidth: 0
				});
				square.on('mouseenter', function(){
					activeBox = this;
					//console.log(activeBox.attrs.boardcoord);
				});
			
				return square;
			};
			
			//return output from function
			return chessBoardLayer;
		}
	]);