<?php

namespace ChessDimension\ChessGame;

class TestChess{

	//PROPERTIEs
	protected $chess_errors = array(
		'CHESS_GAME_ERROR'	=> 'Message %s %s %sto say for that error',
	);
	
	//new TestChess(new Standard);
	public __construct(){
		//setup any instantiations
		
		//setup the board and all positions
	}
	
	//$move = 'NE4'
	public function check_move($move, $player, $board_state){
	
		//could be 'pawn'
		$character = $this->get_character($move);		
		//'XY' = 'E2' => '22'
		$original_position = $this->get_original_position($move, $board_state);
		//'XY' = 'E4'
		$new_position = $this->get_new_position($move);
		//'white', 'black'
		$player = $player;
		
		//call the characters
		$this->$character();
		
		//pawn rules
		//pawn can move once forwads always
			//UNLESS
			//SOMETHING BLOCKING IT
			//check
		//pawn can move two spaces forwards
			//UNLESS 
			//NOT FIRST TURN
			//check
		//pawn can attack 1 space diagonally
			//UNLESS
			//check
		
	
	
		FB::log($move);
		FB::log($board_state);
	}
	
	//expect original position E4
	public function vector($original_position, $new_position){
	
		$vector = array(
			'originalX' => $original_position[0],
			'originalY'	=> $original_position[1],
			'newX'		=> $new_position[0],
			'newY'		=> $new_position[1],
		);
		
		$alphabet = array(1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g', 8 => 'h');
		
		for($i = 1; $i < 9; $i++){
			
			$char = $alphabet[$i];
			
			if(strtolower($vector['originalX']) == $char){
			
				$vector['originalX'] = $i;
			
			}
			
			if(strtolower($vector['newX']) == $char){
			
				$vector['newX'] = $i;
			
			}
			
		}
		
		//returns an array of movement vector in integers, so it's easier to calculate
		return $vector;
	
	}
	
	public function pawn($original_position, $new_position){
	
		//find out what the vector is
		//if($vector == 1 move ahead
		//X movement
		//Y movement
		//E2 to E4
		//E minus the E
		//4 minus 2
	
	}
	
	//ONLY TWO METHODS HERE!
	
	//check move
	
	//board state
	
	//$output = $this->chessengine->check_move($SANnotation, $FENboard_state);
	//$output = 'success'
	//$output could be 'error'
	//$output could also be 'check'
	//$output could be 'checkmate'
	//$output could be 'draw'	
	

}