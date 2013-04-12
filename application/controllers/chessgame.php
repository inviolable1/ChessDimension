<?php

class ChessGame extends CI_Controller{

	private $chess_validator;

	public function __construct(){
	
		parent::__construct();
		$this->chess_validator = new ChessValidator;	//loads the library ChessValidator
	
	}
	
	public function index(){

		//FEN info from last FEN in database, or just data fields. Here I just put in dummy data. This is before move.
		$active_color = 'W';	
		$castling_availability = 'KQkq';	
		$enpassant_target_square = '';	
		$halfmove_clock = 0;	
		$fullmove_number = 1;		

		//Move to be made now
		$piece = 'P'; 
		$old_position = array(5,2); //actual XY coordinate
		$new_position = array(5,4);
		
		//positions array from database (WHITE IS ON TOP). White pieces are capitalised, Black pieces are not. Y co-ordinate goes DOWN, X co-ordinate goes the right. NOTE: this config is achieved after the array_reverse, i.e. in $positions. The reason I do it this way is so that it is easy to enter positions - like how you would view it in an online chess game.
		$positions = array_reverse(array( 
			array(1=>'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'),
			array(1=>'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),			
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
			array(1=>'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'),
			array(1=>'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'),
		));
/*
		//testing if FEN works - using budapest game Ashot-Wu (WORKS! Gives identical FEN to that from Chess.com)
		$positions = array_reverse(array( 
			array(1=>'', '', '', '', 'r', '', 'k', ''),
			array(1=>'b', 'p', 'p', '', '', 'p', 'p', 'p'),
			array(1=>'',   '',  '',  '',  '',  '',  'r',  ''),
			array(1=>'p',   '',  '',  'P',  '',  '',  '',  ''),			
			array(1=>'',   '',  '',  '',  '',  '',  'n',  ''),
			array(1=>'',   'P',  '',  '',  'q',  'N',  'P',  'b'),
			array(1=>'P', 'B', '', '', 'B', 'P', '', 'P'),
			array(1=>'R', '', '', 'Q', 'R', '', 'K', ''),
		));
*/		
		//reindex so line with 1st rank are indexed to array key of 1
		$positions = array_combine(range(1,count($positions)),array_values($positions));
		
		FB::log($positions);
		
		$this->chess_validator->setup_board();
		
		$valid_move = $this->chess_validator->validate($piece, $old_position, $new_position);
		
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

			//new FEN variables (other than board position)
				//use a function in ChessValidator to set the FEN variables for the position after the validated move given the old variables from database
				//$fen_variables 
			
			//FEN of new board position
			echo "<br/></br>FEN of New Board Position <br/>";
			$new_fen = $this->chess_validator->get_fen($new_board_position);
			echo $new_fen;
			
		}
	
	}

}