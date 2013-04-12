<?php

class ChessValidator{

	/* ==========================================================================
		VARIABLES
	   ========================================================================== */
	private $validator;
	private $positions;
	private $errors=array();

	// ***** All possible squares piece can move to (new_position) ***** //
	private $valid_squares = array(
		array(1,1,), array(1,2,), array(1,3,), array(1,4,), array(1,5,), array(1,6,), array(1,7,), array(1,8,),
		array(2,1,), array(2,2,), array(2,3,), array(2,4,), array(2,5,), array(2,6,), array(2,7,), array(2,8,),
		array(3,1,), array(3,2,), array(3,3,), array(3,4,), array(3,5,), array(3,6,), array(3,7,), array(3,8,),
		array(4,1,), array(4,2,), array(4,3,), array(4,4,), array(4,5,), array(4,6,), array(4,7,), array(4,8,),
		array(5,1,), array(5,2,), array(5,3,), array(5,4,), array(5,5,), array(5,6,), array(5,7,), array(5,8,),
		array(6,1,), array(6,2,), array(6,3,), array(6,4,), array(6,5,), array(6,6,), array(6,7,), array(6,8,),
		array(7,1,), array(7,2,), array(7,3,), array(7,4,), array(7,5,), array(7,6,), array(7,7,), array(7,8,),
		array(8,1,), array(8,2,), array(8,3,), array(8,4,), array(8,5,), array(8,6,), array(8,7,), array(8,8,),
	);	
	
	// ***** All possible Move Vectors of the pieces regardless of special conditions ***** //
	private $valid_vectors = array(
		'R' => array(
			//move vertically backwards
			array('0', '-7'), array('0', '-6'), array('0', '-5'), array('0', '-4'), array('0', '-3'), array('0', '-2'), array('0', '-1'),
			//move vertically forwards
			array('0', '1'), array('0', '2'), array('0', '3'), array('0', '4'), array('0', '5'), array('0', '6'), array('0', '7'),
			//move horizontally to the left
			array('-7', '0'), array('-6', '0'), array('-5', '0'), array('-4', '0'), array('-3', '0'), array('-2', '0'), array('-1', '0'),
			//move horizontally to the right
			array('1', '0'), array('2', '0'), array('3', '0'), array('4', '0'), array('5', '0'), array('6', '0'), array('7', '0'),			
		),
		
		'N'	=> array(
			//a knight has 8 legal move vectors
			array('-1', '2'), array('-1', '-2'), array('1' , '2'), array('1', '-2'), array('2', '1'), array('2', '-1'), array('-2', '1'), array('-2', '-1'),
		),

		'B' => array(
			//move diagonally bottom left to top right
			array('1', '1'), array('2', '2'), array('3', '3'), array('4', '4'), array('5', '5'), array('6', '6'), array('7', '7'),
			//move diagonally top right to bottom left
			array('-7', '-7'), array('-6', '-6'), array('-5', '-5'), array('-4', '-4'), array('-3', '-3'), array('-2', '-2'), array('-1', '-1'),
			//move diagonally bottom right to top left
			array('-7', '7'), array('-6', '6'), array('-5', '5'), array('-4', '4'), array('-3', '3'), array('-2', '2'), array('-1', '1'),
			//move diagonally top left to bottom right
			array('1', '-1'), array('2', '-2'), array('3', '-3'), array('4', '-4'), array('5', '-5'), array('6', '-6'), array('7', '-7'),		
		),
		
		'Q' => array(
			//combination of Rook and Bishop
			//move vertically backwards
			array('0', '-7'), array('0', '-6'), array('0', '-5'), array('0', '-4'), array('0', '-3'), array('0', '-2'), array('0', '-1'),
			//move vertically forwards
			array('0', '1'), array('0', '2'), array('0', '3'), array('0', '4'), array('0', '5'), array('0', '6'), array('0', '7'),
			//move horizontally to the left
			array('-7', '0'), array('-6', '0'), array('-5', '0'), array('-4', '0'), array('-3', '0'), array('-2', '0'), array('-1', '0'),
			//move horizontally to the right
			array('1', '0'), array('2', '0'), array('3', '0'), array('4', '0'), array('5', '0'), array('6', '0'), array('7', '0'),			
			//move diagonally bottom left to top right
			array('1', '1'), array('2', '2'), array('3', '3'), array('4', '4'), array('5', '5'), array('6', '6'), array('7', '7'),
			//move diagonally top right to bottom left
			array('-7', '-7'), array('-6', '-6'), array('-5', '-5'), array('-4', '-4'), array('-3', '-3'), array('-2', '-2'), array('-1', '-1'),
			//move diagonally bottom right to top left
			array('-7', '7'), array('-6', '6'), array('-5', '5'), array('-4', '4'), array('-3', '3'), array('-2', '2'), array('-1', '1'),
			//move diagonally top left to bottom right
			array('1', '-1'), array('2', '-2'), array('3', '-3'), array('4', '-4'), array('5', '-5'), array('6', '-6'), array('7', '-7'),		
		),
		
		'K' => array(
			//a King has 8 legal move vectors
			array('-1','-1'),
			array('-1','0'),
			array('-1','1'),
			array('0','-1'),
			array('0','1'),
			array('1','-1'),
			array('1','0'),
			array('1','1'),
		),			
		
		'P' => array(
			array('0', '2'),
			array('0', '1'),
			array('1', '1'),
			array('-1', '1'),
		),
		
		'p' => array(
			array('0', '-2'),
			array('0', '-1'),
			array('1', '-1'),
			array('-1', '-1'),
		),
	);
	
	/* ==========================================================================
		CONSTRUCTOR FUNCTION - Load Validator (to check for e.g. if move notation is valid
	   ========================================================================== */
	   
	public function __construct($validator = false){
		//By default it is false - i.e. will not load a validator. If a $validator is passed into this, e.g. Polycademy Validator, it will load it. 
		$this->validator = $validator;
	}
	/* ==========================================================================
		SETUP BOARD POSITION FUNCTION
	   ========================================================================== */
	public function setup_board($positions = false){
	
		//If position is passed in from chessgame, use that position. Otherwise, use the default starting position in else.
		if($positions){
			$this->positions = $positions;
		}else{
			//Default Starting Position
				//assumes White on the Bottom and Black on the Top, this is flipped for calculation
				//(Y co-ordinate goes DOWN, X co-ordinate goes the right
			$this->positions = array(
				1 =>array(1=>'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R'),
					array(1=>'P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'),
					array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
					array(1=>'',   '',  '',  '',  '',  '',  '',  ''),			
					array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
					array(1=>'',   '',  '',  '',  '',  '',  '',  ''),
					array(1=>'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'),
					array(1=>'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'),
			);
		}
		//FB::log($this->positions);
	}
	
	/* ==========================================================================
		CHESS MOVE VALIDATION (HIGH LEVEL) - Using Low Level functions
	   ========================================================================== */
	public function validate($piece, $old_position, $new_position, $move_count){
	
		$data = array(
			'piece'			=> $piece,
			'old_position'	=> $old_position,
			'new_position'	=> $new_position,
			'move_count'	=> $move_count,
		);

		// ***** Data Validator ***** //
			//Check if data entered are valid in the first place
		if($this->validator){	//note: CAREFUL! this could mean if this->validator exists, or it could mean if $this->validator returned false
			//do the validation check
			//validate that $piece is like WB
			
			/*
			$this->validator->setup_rules(array(
				'piece'	=> array(
					'set_label:Chess Piece',
					'NotEmpty',
					'MinLength:2',
				)
			));
			*/
		}
		
		// ***** Checker: If new_position is on the board at all ***** //
			//if it is not on board, don't carry out further checks
		if(!$this->checker_out_of_board($data['new_position'])){
			return false;
		}
		
		// ***** Get the vector of the move and add it to the 'data' array ***** //
		$data['vector'] = $this->get_vector($data['old_position'], $data['new_position']);
				
		// ***** Global Checker ***** //
			//checking that the position is actually on the board
			//checking if the person is in check, checkmate etc.

		// ***** Special Condition Checkers ***** //
			// checker for (pawn double movement), (pawn front block), (king castling), (en passant)
						
		// ***** Check the vector against the list of legal vectors ***** //
			//if the move is not even possible, e.g. bishop moving like a rook, don't carry out further checks
		if(!$this->checker_vector($data['piece'], $data['vector'])){
			return false;
		}
		
		//***** Blocking Checker (including if there is your own piece on a square you want to move a piece to - you can't capture your own piece)
			//if the block check fails, don't carry out further checks
		if(!$this->checker_block($data['piece'], $data['vector'], $data['old_position'], $data['new_position'], $this->positions)){
			return false;
		}
		
		//GLOBAL CHECKER
			//e.g. to check if the check has been avoided
		
		//if there are no errors, return the data
		if(empty($this->errors)){
			return $data;
		}else{
			return false;
		}
		
	}
	
	/* ==========================================================================
		CHESS MOVE VALIDATION (LOW LEVEL)
	   ========================================================================== */	
	public function checker_out_of_board($new_position){
		
		$valid_squares = $this->valid_squares;
		
		foreach($valid_squares as $possible_squares){
		
			if($possible_squares == $new_position){
				//this means it is a valid move
				return true;
			}
			
		}
		
		$this->errors += array(
			'vector_error'	=> 'The destination square ' . implode(',', $new_position) . ' is not within the board',
		);
		
		return false;
		
	}		
	
	public function get_vector($old_position, $new_position){	//note: both parameters here are arrays
	
		//the vector is the x and y move range
		$vector = array(
			$new_position[0] - $old_position[0],
			$new_position[1] - $old_position[1],
		);
		
		return $vector;
		
	}

	public function checker_vector($piece, $vector){
	
		//convert the $piece, into the actual piece, and player
		if(ctype_upper($piece)){
			$player = 'W';
		}else
		{
			$player = 'B';
		}
		
		if($piece !== 'p'){		//unless the piece is a pawn, change it to capital because it does not affect movement
			$piece = strtoupper($piece);
		}		
				
		//$piece could be N or P (white pawn) or p (black pawn)
		$valid_vectors = $this->valid_vectors[$piece];
		
		foreach($valid_vectors as $possible_vector){
		
			if($possible_vector == $vector){
				//this means it is a valid move
				return true;
			}
			
		}
		
		$this->errors += array(
			'vector_error'	=> $player . ' using ' . $piece . ' cannot move with the vector of ' . implode(',', $vector),
		);
		
		return false;
	
	}
	
	public function checker_block($piece, $vector, $old_position, $new_position, $board_positions){
	
		//convert the $piece, into the actual piece, and player
		if(ctype_upper($piece)){
			$player = 'W';
		}else
		{
			$player = 'B';
		}
		
		if($piece !== 'p'){		//unless the piece is a pawn, change it to capital because it does not affect movement
			$piece = strtoupper($piece);
		}	
		
		//check destination square to check if it is currently occupied by one of your own pieces
		//if the final position has a piece that is owned by the same player, fail as well
		$final_piece = $board_positions[$new_position[1]][$new_position[0]];
		//FB::log($final_piece);
		//FB::log($board_positions);
		if(!empty($final_piece)){
		
			//something is occupying the final position			
			FB::log('Final piece was not empty');
			if(ctype_upper($final_piece)){
				$player_final_piece = 'W';
			}else
			{
				$player_final_piece = 'B';
			}
			
			if($player_final_piece == $player) {
				FB::log('The final piece is owned by the player!');
				$this->errors += array(
					'block_error'	=> 'The ' . $piece . ' cannot move to ' . implode(',', $new_position) . ' because there is a piece occupying that position and the player owns it.',
				);
				return false;
			}
			
		}
		
		//if $piece is a knight or king, exempt from further blocking checks
		if($piece == 'N' OR $piece == 'K'){
			return true;
		}
		
		//create the step that we'll go through in order to iterate through the board positions to check blocking
		$step = array();
		foreach($vector as $direction){
			if($direction < 0){
				
				$step[] = -1;
				
			}elseif($direction === 0){
			
				$step[] = 0;
			
			}elseif($direction > 0){
			
				$step[] = 1;
			
			}
		}
		FB::log($step, 'STEP ->');
		
		//store the current position for iteration
		$current_position = $old_position;
		
		//add the step to the current position (we dont check the old_position)
		$current_position[0] += $step[0];
		$current_position[1] += $step[1];
		
		//FB::log($current_position);
		//FB::log($new_position);
		
		//this will check if we have reached the new position
		while($current_position != $new_position){
			
			//FB::log($board_positions[$current_position[1]][$current_position[0]],'Piece at position');
			
			//use the X, Y co-ordinate and query the board if it has a element
			if(!empty($board_positions[$current_position[1]][$current_position[0]])){
			
				//something is occupying the position that is in between the old position and new position
				//therefore return false
				$this->errors += array(
					'block_error'	=> 'The ' . $piece . ' cannot move to ' . implode(',', $new_position) . ' because it is blocked by a piece in between.',
				);
				return false;
				
			}

			$current_position[0] += $step[0];
			$current_position[1] += $step[1];

		}

		return true;
		
	}

	/* ==========================================================================
		GET NEW BOARD POSITION 
	   ========================================================================== */	
		function get_new_board_position($piece,$old_position,$new_position,$positions) {
			
			$new_board_position = $positions;

			//remove piece from old position on the board
			$new_board_position[$old_position[1]][$old_position[0]] = '';
			
			//place piece on new position on the board
			$new_board_position[$new_position[1]][$new_position[0]] = $piece;
			
			return $new_board_position;
		
		}	   
	   
	/* ==========================================================================
		GET FEN OF BOARD
	   ========================================================================== */	
		
		// ***** get FEN of board position ***** //
		function get_fen($positions) {
		
			$temp = array();
			
			foreach($positions as $rank){
				$temp = $rank;
				FB::log(implode('', $temp));
			}

		
		}
			

	/* ==========================================================================
		SAN OF MOVE
	   ========================================================================== */	
		function get_san($piece,$old_position,$new_position) {	
		//need old position to check for case where two pieces can move to the same square
		
			//player making move
			if(ctype_upper($piece)){
				$player = 'W';
			}else
			{
				$player = 'B';
			}
		
			//pawns don't have a piece notation in SAN, all other pieces are in capitals
			if(strtoupper($piece) === 'P'){
				$piece = '';
			}else
			{
				$piece = strtoupper($piece);
			}
			
			//SAN for square moved to
			$new_position_san = chr($new_position[0]+96) . $new_position[1];
			
			//SAN for overall move
			$san = $piece . $new_position_san;
			
			return array($player,$san);
			
		}
					   
	/* ==========================================================================
		FUNCTION TO GET ERRORS
	   ========================================================================== */	
	public function get_errors(){
		return $this->errors;
	}
	
}