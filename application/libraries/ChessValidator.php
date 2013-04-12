<?php

class ChessValidator{

	/* ==========================================================================
		VARIABLES
	   ========================================================================== */
	private $validator;
	private $positions;
	private $errors;

	// ***** All possible Move Vectors of the pieces regardless of special conditions ***** //
	private $valid_vectors = array(
		'N'	=> array(
			array('-1', '2'),
			array('-1', '-2'),
			array('1' , '2'),
			array('1', '-2'),
			array('2', '1'),
			array('2', '-1'),
			array('-2', '1'),
			array('-2', '-1'),
		),
		'WP' => array(
			array('0', '2'),
			array('0', '1'),
			array('1', '1'),
			array('-1', '1'),
		),
		'BP' => array(
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
				1	=>	array(1=>'WR', 'WN', 'WB', 'WQ', 'WK', 'WB', 'WN', 'WR'),
						array(1=>'WP', 'WP', 'WP', 'WP', 'WP', 'WP', 'WP', 'WP'),
						array(1=>'', '', '', '', '', '', '', ''),
						array(1=>'', '', '', '', '', '', '', ''),
						array(1=>'', '', '', '', '', '', '', ''),
						array(1=>'', '', '', '', '', '', '', ''),
						array(1=>'BP', 'BP', 'BP', 'BP', 'BP', 'BP', 'BP', 'BP'),
						array(1=>'BR', 'BN', 'BB', 'BQ', 'BK', 'BB', 'BN', 'BR'),
			);
		}
		
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
		if($this->validator){
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
		
		// ***** Get the vector of the move and add it to the data array ***** //
		$data['vector'] = $this->get_vector($data['old_position'], $data['new_position']);
				
		// ***** Global Checker ***** //
			//checking that the position is actually on the board
			//checking if the person is in check, checkmate etc.

		// ***** Special Condition Checkers ***** //
			// checker for (pawn double movement), (pawn front block), (king castling), (en passant)
						
		// ***** Check the vector against the list of legal vectors ***** //
		$this->checker_vector($data['piece'], $data['vector']);
		
		//***** Blocking Checker (knight is exempt)
		$this->checker_block($data['piece'], $data['vector'], $data['old_position'], $data['new_position'], $this->positions);
		
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
		CHESS MOVE VALIDATION (LOW LEVEL), including get errors
	   ========================================================================== */	
	public function get_vector($old_position, $new_position){	//note: both parameters here are arrays
	
		//the vector is the x and y move range
		$vector = array(
			$new_position[0] - $old_position[0],
			$new_position[1] - $old_position[1],
		);
		
		return $vector;
		
	}

	public function checker_vector($piece, $vector){
	
		//split the $piece, into the actual piece, and player
		$temp = str_split($piece);
		$player = $temp[0];
		if($temp[1] != 'P'){
			$piece = $temp[1];
		}
		
		//$piece could be N or WP or BP
		$valid_vectors = $this->valid_vectors[$piece];
		
		foreach($valid_vectors as $possible_vector){
		
			$difference = array_diff($possible_vector, $vector);
			if(empty($difference)){
				//this means its a valid move
				return true;
			}
			
		}
		
		$this->errors = array(
			'vector_error'	=> $player . ' using ' . $piece . ' cannot move with the vector of ' . implode(',', $vector),
		);
		
		return false;
	
	}
	
	public function checker_block($piece, $vector, $old_position, $new_position, $board_positions){
	
		$temp = str_split($piece);
		$player = $temp[0]; //could be B or W
		$piece = $temp[1];
		FB::log($player);
		FB::log($piece);
	
		//if $piece is a knight, return true
		if($piece == 'N'){
			return true;
		}
		FB::log('Not a knight');
		
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
		
		//if there the final position has a piece that is owned by the same player, fail as well
		$final_piece = $board_positions[$new_position[1]][$new_position[0]];
		FB::log($final_piece);
		FB::log($board_positions);
		if(!empty($final_piece)){
			
			FB::log('Final piece was not empty');
			
			FB::log($player);
			FB::log(str_split($final_piece)[0]);
			//something is occupying the final position
			if(str_split($final_piece)[0] == $player){
				FB::log('The final piece is owned by the player!');
				$this->errors = array(
					'block_error'	=> 'The ' . $piece . ' cannot move to ' . implode(',', $new_position) . ' because there is a piece occupying that position and the player owns it.',
				);
				return false;
			}
			
		}
		
		//store the current position for iteration
		$current_position = $old_position;
		$reached_end = array_diff($current_position, $new_position);
		//this will check if we have reached the new position
		while(!empty($reached_end)){ //while we havent reached it...
			
			//add the step the current position (we dont check the old_position)
			$current_position[0] += $step[0];
			$current_position[1] += $step[1];
			
			//use the X, Y co-ordinate and query the board if it has a element
			if(!empty($board_positions[$current_position[1]][$current_position[0]])){
			
				//something is occupying the position that is in between the old position and new position
				//therefore return false
				$this->errors = array(
					'block_error'	=> 'The ' . $piece . ' cannot move to ' . $new_position . ' because it is blocked by a piece in between.',
				);
				return false;
				
			}
			
			//recycle the $reached_end
			$reached_end = array_diff($current_position, $new_position);
			
		}		
		
		return true;
		
	}
	
	public function get_errors(){
		return $this->errors;
	}
	
}