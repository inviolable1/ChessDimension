<?php

class ChessValidator{	
//Note: I interchanged used of === and == in if statements when it did not involve a number, need to make this consistent

	/* ==========================================================================
		VARIABLES
	   ========================================================================== */
	private $validator;
	private $positions;
	private $errors=array();
	private $capture = false;	//whether something has been captured (used in destination square check of checker_block)
	
	// for FEN	
	private $active_color;	//who moves next
	private $castling_availability;	//only whether technically can, don't factor in checks etc.
	private $enpassant_target_square;	//irregardless of whether there is a pawn in position to make the enpassant capture
	private $halfmove_clock;	//to check for 50 move rule
	private $fullmove_number;	//Number of full moves played, incremented after Black's move	

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
		CONSTRUCTOR FUNCTION - Load Validator (to check for e.g. if move notation is valid)
	   ========================================================================== */
	   
	public function __construct($validator = false){
		//By default it is false - i.e. will not load a validator. If a $validator is passed into this, e.g. Polycademy Validator, it will load it. 
		$this->validator = $validator;
	}
	/* ==========================================================================
		SETUP BOARD POSITION AND VARIABLES FUNCTION (DO FEN HERE INSTEAD??-THIS IS PRETTY MUCH WHAT IT DOES)
	   ========================================================================== */
	public function setup_board($positions = false,$active_color = false, $castling_availability = false, $enpassant_target_square = false, $halfmove_clock = false, $fullmove_number = false){
	
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
		
		if($active_color){
			$this->active_color = $active_color;
		}else{
			$this->active_color = 'W';
		}
		
		if($castling_availability){
			$this->castling_availability = $castling_availability;
		}else{
			$this->castling_availability = 'KQkq';
		}
		
		if($enpassant_target_square){
			$this->enpassant_target_square = $enpassant_target_square;
		}else{
			$this->enpassant_target_square = '-';
		}
		
		if($halfmove_clock){
			$this->halfmove_clock = $halfmove_clock;
		}else{
			$this->halfmove_clock = 0;
		}
		
		if($fullmove_number){
			$this->fullmove_number = $fullmove_number;
		}else{
			$this->fullmove_number = 1;
		}

	}
	
	/* ==========================================================================
		CHESS MOVE VALIDATION (HIGH LEVEL) - Using Low Level functions
	   ========================================================================== */
	public function validate($piece, $old_position, $new_position, $promote_piece){
	
		$data = array(
			'piece'			=> $piece,
			'old_position'	=> $old_position,
			'new_position'	=> $new_position,
			'promote_piece' => $promote_piece
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
		
		// ***** Checker: If piece is a valid piece, i.e. P, R, N, B, Q, K ***** //
		if(!$this->checker_valid_piece($data['piece'])){
			return false;
		}
		
		// ***** Checker: If the move made was done on correct turn ***** // 
		if(!$this->checker_correct_turn($data['piece'])){
			return false;
		}
		
		// ***** Checker: If old_position of piece is on the board at all ***** //
			//if it is not on board, don't carry out further checks
		if(!$this->checker_out_of_board($data['old_position'])){
			return false;
		}		
		
		// ***** Checker: If the old position of the piece is correct, e.g. is d2 in the position before move really a pawn, if you're trying to move d2 - d4? This test will return false if d2 is empty, or occupied by something else. ***** //
		if(!$this->checker_piece_exists($data['piece'],$data['old_position'],$this->positions)){
			return false;
		}
		
		// ***** Checker: If new_position of piece is on the board at all ***** //
			//if it is not on board, don't carry out further checks
		if(!$this->checker_out_of_board($data['new_position'])){
			return false;
		}
		
		// ***** Checker: If promote_piece is not empty when piece is not a pawn. 
		if($data['piece'] !== 'p' AND $data['piece'] !== 'P'){
			if($data['promote_piece']){
				$this->errors += array(
					'promote_error'	=> $data['piece'] . ' is not a pawn so there can be no promote_piece',
				);				
				return false;
			}
		}				
		
		// ***** Get the vector of the move and add it to the 'data' array ***** //
		$data['vector'] = $this->get_vector($data['old_position'], $data['new_position']);
				
		// ***** Global Checker ***** //
			//checking that the position is actually on the board
			//checking if the person is in check, checkmate etc.

		// ***** Special Condition Checkers ***** //
			//checker for pawn special moves
		if($data['piece'] == 'p' OR $data['piece'] == 'P') {
		
			//pawn moving two steps (do not allow unless first move)
			if(!$this->checker_pawn_two_steps($data['piece'], $old_position, $data['vector'])){
				return false;
			}
			
			//pawn moving diagonally (do not allow unless capturing)
			if(!$this->checker_pawn_move_diagonal($data['piece'], $data['new_position'], $data['vector'], $this->positions)){
				return false;
			}
			
			//pawn moving forward (do not allow if there is something blocking on the destination square)
			if(!$this->checker_pawn_move_forward($data['piece'], $data['new_position'], $data['vector'], $this->positions)){
				return false;
			}
			
			//pawn promotion (promote_piece must be empty	unless new_position is on 1st/8th rank. Also, it cannot be empty if it is 1st/8th rank. Also, $promote_piece must be one of R, N, B, Q or r, n, b, q. $promote_piece must be same color as $piece
			if(!$this->checker_pawn_promotion($data['piece'],$data['new_position'],$data['promote_piece'])){
				return false;
			}			
			
		}		
			
			//other special condition checks: (king castling), (en passant)
						
		// ***** Check the vector against the list of legal vectors ***** //
			//if the move is not even possible, e.g. bishop moving like a rook, don't carry out further checks
		if(!$this->checker_vector($data['piece'], $data['vector'])){
			return false;
		}
		
		// ***** Blocking Checker (including if there is your own piece on a square you want to move a piece to - you can't capture your own piece)
			//if the block check fails, don't carry out further checks
		if(!$this->checker_block($data['piece'], $data['vector'], $data['old_position'], $data['new_position'], $this->positions)){
			return false;
		}
		
		// ***** GLOBAL CHECKER ***** //
			//e.g. to check if the check has been avoided
		
		// ***** if there are no errors, change FEN variables, checks for draw/checkmate and then return the data ***** //
		if(empty($this->errors)){
			//passes validation
			
			//checks for draw/checkmate - these need to be reported as well
			
			//change FEN variables
				//change active color
			if($this->active_color == 'W'){
				$this->active_color = 'B';
			}else{
				$this->active_color = 'W';
			}
				//change
			
			FB::log($this->active_color, 'New active color');
			return $data;
			
		}else{
			//fails validation
			//don't think this section is needed because we already return false once there is error
			
			//CHECK: DO I NEED TO RESET VARIABLES? OR IS THERE A NEW CHESS VALIDATOR INSTANCE CREATED EACH TIME BY PHP FOR A NEW MOVE? DON'T THINK NEED TO RESET. AT LATER STAGE, THE CONTROLLER IN CHESSGAME.PHP WILL CALL A MODEL TO DO ALL THIS VALIDATION. IT CALLS THE MODEL WITH EACH NEW MOVE, SO THE MODEL WILL SPIN UP A NEW INSTANCE OF VALIDATOR (I THINK)
			return false;
		}
		
	}
	
	/* ==========================================================================
		CHESS MOVE VALIDATION (LOW LEVEL) - RECYCLABLES AND NON-GLOBAL AND NON-SPECIAL-CONDITION
	   ========================================================================== */	
	public function checker_valid_piece($piece){
		
		$valid_pieces = array('R','N','B','Q','K','P');
		
		foreach($valid_pieces as $valid){
		
			if($valid == strtoupper($piece)){
				return true;
			}
			
		}

		$this->errors += array(
			'validity_error'	=> $piece . ' is not a valid chess piece',
		);
		
		return false;
	}
		

	public function checker_correct_turn($piece){
		
		if(ctype_upper($piece)){
			$player = 'W';
		}else{
			$player = 'B';
		}
		
		if($player != $this->active_color){

			$this->errors += array(
				'turn_error' => 'It is not ' . $player . '\'s turn to move',
			);
		
			return false;			
		}
		
		return true;
		
	}
	
	public function checker_out_of_board($position){
		
		$valid_squares = $this->valid_squares;
		
		foreach($valid_squares as $possible_squares){
		
			if($possible_squares == $position){
				//this means it is a valid move
				return true;
			}
			
		}
		
		$this->errors += array(
			'vector_error'	=> 'The origin/destination square ' . implode(',', $position) . ' is not within the board',
		);
		
		return false;
		
	}		
	
	public function checker_piece_exists($piece,$old_position,$board_positions){
		
		if($piece == $board_positions[$old_position[1]][$old_position[0]]){
			return true;
		}
		
		$this->errors += array(
			'existence_error'	=> 'The piece does not exist on the old position of ' . implode(',' , $old_position),
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
		//($final_piece);
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
				FB::log($this->capture, 'Capture made?');
				$this->errors += array(
					'block_error'	=> 'The ' . $piece . ' cannot move to ' . implode(',', $new_position) . ' because there is a piece occupying that position and the player owns it.',
				);
				return false;
			}
			
			$this->capture = true;
			FB::log($this->capture, 'Capture made?');
			
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
		CHESS MOVE VALIDATION (LOW LEVEL) GLOBAL 
	   ========================================================================== */	
	   function is_square_under_attack(){
			
		}
	   
	/* ==========================================================================
		CHESS MOVE VALIDATION (LOW LEVEL) SPECIAL CONDITION
	   ========================================================================== */	   
	   function checker_pawn_two_steps($pawn, $old_position, $vector){
			//if it enters in here, it must already be verified to be a pawn
			
			//if it is a white pawn, i.e. P
			if($pawn == 'P') {
				if($vector == array(0,2)) {	
					if($old_position[1] != 2) {
						$this->errors += array(
							'pawn_error'	=> 'A pawn can only move 2 steps on its first move',
						);					
						return false;
					}
				}
			}else{	//if it is a black pawn, i.e. p
				if($vector == array(0,-2)) {	
					if($old_position[1] != 7) {
						$this->errors += array(
							'pawn_error'	=> 'A pawn can only move 2 steps on its first move',
						);					
						return false;
					}
				}			
			}
			
			return true;
		}
		
		function checker_pawn_move_diagonal($pawn, $new_position, $vector, $board_positions) {
			//if it enters in here, it must already be verified to be a pawn
			
			//if it is a white pawn, i.e. P
			if($pawn == 'P') {
				if($vector == array(1,1) OR $vector == array(-1,1)) {
					if(!$board_positions[$new_position[1]][$new_position[0]]){
						$this->errors += array(
							'pawn_error'	=> 'A pawn can only move diagonally to make a capture',
						);					
						return false;
					}
				}
			}else{	//if it is a black pawn, i.e. p
				if($vector == array(1,-1) OR $vector == array(-1,-1)) {
					if(!$board_positions[$new_position[1]][$new_position[0]]){
						$this->errors += array(
							'pawn_error'	=> 'A pawn can only move diagonally to make a capture',
						);					
						return false;
					}
				}
			}
			
			return true;	
		}
		
		function checker_pawn_move_forward($pawn, $new_position, $vector, $board_positions) {
			//if it enters in here, it must already be verified to be a pawn

			//if it is a white pawn, i.e. P
			if($pawn == 'P') {
				if($vector == array(0,1) OR $vector == array(0,2)) {
					if($board_positions[$new_position[1]][$new_position[0]]){
						$this->errors += array(
							'pawn_error'	=> 'A pawn cannot move forward if there is something blocking on the destination square',
						);					
						return false;
					}
				}
			}else{	//if it is a black pawn, i.e. p
				if($vector == array(0,-1) OR $vector == array(0,-2)) {
					if($board_positions[$new_position[1]][$new_position[0]]){
						$this->errors += array(
							'pawn_error'	=> 'A pawn cannot move forward if there is something blocking on the destination square',
						);					
						return false;
					}
				}
			}
			
			return true;
		}
		
		function checker_pawn_promotion($pawn, $new_position, $promote_piece){
			//if it enters in here, it must already be verified to be a pawn

			//if it is a white pawn, i.e. P
			if($pawn == 'P') {
				if($new_position[1] != 8){
					if($promote_piece !== ''){
						$this->errors += array(
							'promotion_error'	=> 'There can be no promote_piece unless a pawn is moving to the first/last rank',
						);			
						return false;
					}
				}else{
					if($promote_piece == ''){
						$this->errors += array(
							'promotion_error'	=> 'There must be a promote_piece specified when a pawn is moving to the first/last rank',
						);
						return false;
					}
				}			
						
			}else{	//if it is a black pawn, i.e. p	
				if($new_position[1] != 1){
					if($promote_piece !== ''){
						$this->errors += array(
							'promotion_error'	=> 'There can be no promote_piece unless a pawn is moving to the first/last rank',
						);			
						return false;
					}
				}else{
					if($promote_piece == ''){
						$this->errors += array(
							'promotion_error'	=> 'There must be a promote_piece specified when a pawn is moving to the first/last rank',
						);
						return false;
					}
				}		
			
			}
			
			//check if $promote_piece is the same color as $pawn, if $promote_piece is not empty
			if($promote_piece !== ''){
				if(ctype_upper($pawn) !== ctype_upper($promote_piece)){
					$this->errors += array(
						'promotion_error'	=> 'The promotion piece must be of the same color as the pawn being promoted',
					);
					return false;
				}
			}

			//check if $promote_piece is a valid promotion piece, if $promote_piece is not empty
			if($promote_piece !== ''){			
				if(strtoupper($promote_piece) != 'R' AND strtoupper($promote_piece) != 'N' AND strtoupper($promote_piece) != 'B' AND strtoupper($promote_piece) != 'Q'){
					$this->errors += array(
						'promotion_error'	=> $promote_piece . ' is not a valid promotion piece',
					);							
					return false;
				}
			}
			
			return true;
		
		}	
	
	/* ==========================================================================
		GET NEW BOARD POSITION 
	   ========================================================================== */	
		function get_new_board_position($piece,$old_position,$new_position,$promote_piece,$positions) {
			//note: need to factor in promotion. probably have to add a new parameter
			
			$new_board_position = $positions;

			//remove piece from old position on the board
			$new_board_position[$old_position[1]][$old_position[0]] = '';
			
			//place piece on new position on the board
			if($promote_piece == ''){
				//if there is no promotion
				$new_board_position[$new_position[1]][$new_position[0]] = $piece;
			}else{
				//if there is a promotion
				$new_board_position[$new_position[1]][$new_position[0]] = $promote_piece;
			}				
			
			return $new_board_position;
		
		}	   
	   
	/* ==========================================================================
		GET FEN OF BOARD
	   ========================================================================== */	
		
		// ***** get FEN from board position ***** //
		function get_fen($positions) {
			
			$rank = array();
			$answer = array();
			$fen_array = array();
			
			//$fen_array gets the board position to the form of an array [0] => RNBQKBNR, [1] = PPPPPPPP, etc.
			foreach($positions as $y){
			
				foreach($y as $x){
					if(!$x){
						$rank[] = 1;
					}else{
						$rank[] = $x;
					}					
				}
				
				//get it to the form where if you have just one white pawn on e4 on the 4th rank, it displays 4P3
				$answer[1] = $rank[0];
				
				for ($i = 1; $i <= (count($rank)-1); $i++){
					if($rank[$i] == 1){
						if(is_numeric($answer[count($answer)])){
							$answer[count($answer)] += 1;
						}else{
							$answer[] = 1;
						}
					}else{
						$answer[] = $rank[$i];
					}
				}

				$fen_rank = implode('',$answer);
				//reset arrays to be reused to empty arrays
				$rank = array();
				$answer = array();
				
				$fen_array[] = $fen_rank;

			}
			
			//flip $fen_array to make it abide by FEN (black at top, white at bottom)
			$fen_array = array_reverse($fen_array);
			
			//$fen_main (board position in a string, ranks are seperated by /)
			$fen_main = implode('/',$fen_array);

			//other fen parts such as active color, castling rights, en passant target square, halfmove clock (for 50 move rule) and fullmove number (for movelist)
				//WORK ON THIS
			
			return $fen_main;
		
		}

		// ***** get board position from FEN ***** //
		

	/* ==========================================================================
		SAN OF MOVE (VECTOR to SAN done, need to work on SAN to vector, probably need to incorporate FEN to do this)
	   ========================================================================== */	
		function get_san($piece,$old_position,$new_position, $promote_piece) {	
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
			
			//Does a capture occur at destination square?
			if($this->capture){
				$capture = 'x';
				//if piece is pawn, name it the file of the old square
				if($piece == ''){
					$piece = chr($old_position[0]+96);	
				}
			}else{
				$capture = '';
			}
			
			//SAN for overall move
			$san = $piece . $capture . $new_position_san . $promote_piece;
			
			//add in rules for capture, check, promotion etc.
				//TO DO
			
			return array($player,$san);
			
		}
					   
	/* ==========================================================================
		FUNCTION TO GET ERRORS
	   ========================================================================== */	
	public function get_errors(){
		return $this->errors;
	}
	
}