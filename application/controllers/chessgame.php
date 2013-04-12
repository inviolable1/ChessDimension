<?php

class ChessGame extends CI_Controller{

	private $chess_validator;

	public function __construct(){
	
		parent::__construct();
		$this->chess_validator = new ChessValidator;	//loads the library ChessValidator
	
	}
	
	public function index(){
	
		$piece = 'P'; 
		$old_position = array(5,2); //actual XY coordinate
		$new_position = array(5,4);
		$move_count = 1;
		
		//positions array from database (WHITE IS ON TOP). White pieces are capitalised, Black pieces are not.
		$positions = array( //(Y co-ordinate goes DOWN, X co-ordinate goes the right
		1 =>array(1=>'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'),
			array(1=>'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),			
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'),
			array(1=>'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'),
		);
		
		$this->chess_validator->setup_board($positions);
		
		$valid_move = $this->chess_validator->validate($piece, $old_position, $new_position, $move_count);
		
		if(!$valid_move){
			echo 'Invalid move';
			var_dump($this->chess_validator->get_errors());
		}else{
			//if Valid move
			//SAN of move 
			$san_player = $this->chess_validator->get_san($piece, $old_position, $new_position)[0];
			$san_move = $this->chess_validator->get_san($piece, $old_position, $new_position)[1];
			echo "Valid move by $san_player: ";
			echo $san_move;
			
			//Move Details
			echo "<br/><br/> Move Details";
			var_dump($valid_move);
			
			//new board position (to put into database) after move
			echo "<br/>New Board Position";
			$new_board_position = $this->chess_validator->get_new_board_position($piece, $old_position, $new_position,$positions);
			var_dump($new_board_position);
			
			//FEN of new board position
			echo "<br/>FEN of New Board Position <br/>";
			$new_fen = $this->chess_validator->get_fen($new_board_position);
			echo $new_fen;
		}
	
	}

}