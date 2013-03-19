<?php

use Polycademy\Validation\Validator;
use Polycademy\Validation\Rule;
	
class Chat_model extends CI_model{

	protected $validator;
	protected $errors;
	
	public function __construct(){
	
		parent::__construct();
		$this->validator = new Validator;
		//$this->load->database(); this is not required because we use autoload
		
	}

	//only Create and Read functions required	
	public function create($data){

		$this->validator->setup_rules(array(
			'environment' => array(
				'set_label:Environment',
				'NotEmpty',
				'MaxLength:10',
			),
			'message' => array(
				'set_label:Message',
				'NotEmpty',
				'MaxLength:1000',
			),
		));
		
		if(!$this->validator->is_valid($data)){
		
			//returns array of key for data and value
			$this->errors = $this->validator->get_errors();
			// log_message('error','Validation failed');
			// echo 'Validation Failed <br/>';
			// print_r($this->errors);
			return false;
			
		}
		
		$query = $this->db->insert('chatlogs', $data); 
 
        if(!$query){
 
            $msg = $this->db->_error_message();
            $num = $this->db->_error_number();
            $last_query = $this->db->last_query();
			
            log_message('error', 'Problem Inserting to chatlogs table: ' . $msg . ' (' . $num . '), using this query: "' . $last_query . '"');
			
			$this->errors = array(
				'database'	=> 'Problem inserting data to courses table.',
			);
 
			// print_r($this->errors);
            return false;
			
        }
		
		// echo 'Chat saved! The chat message has an ID of ';
        return $this->db->insert_id();
		
	}
	
	public function read_last($data) {
	
		$this->db->select('*');
		$this->db->from('chatlogs');
		//http://stackoverflow.com/questions/9941566/codeigniter-select-query-with-and-and-or-condition shows how to add multiple where functions
		
		switch($data['environment']){
			case 'main':
				$data['gamedata_id'] = 0;	//coerce it to 0 (need to check how come it matters if it's not 0)
				$this->db->where('environment',$data['environment']);
			case 'game':
				$this->db->where('gamedata_id',$data['gamedata_id']);
		}
		
		$query = $this->db->get(); 
	
		if($query->num_rows() > 0){
			$query = $query->last_row();
			$queryresult = array(
				'id' 			=> $query->id,
				'user_id' 		=> $query->users_id,
				'environment' 	=> $query->environment,
				'gamedata_id' 	=> $query->gamedata_id,
				'message' 		=> $query->message,
			);
			//echo 'Last line of chat in environment';
			return $queryresult;
		}else{
			log_message('error','No chat data available');
			$this->errors = array(
				'database'	=> 'No chat data available.',
			);
			//echo 'No chat data available';

			return false;
		}
	}
	
	public function read_all($data) {
	
		$this->db->select('*');
		$this->db->from('chatlogs');
		
		switch($data['environment']){
			case 'main':
				$data['gamedata_id'] = 0;	//coerce it to 0 (need to check how come it matters if it's not 0)
				$this->db->where('environment',$data['environment']);
			case 'game':
				$this->db->where('gamedata_id',$data['gamedata_id']);
		}
		
		$query = $this->db->get(); 

		if($query->num_rows() > 0){
		
			foreach($query->result() as $row){	
				//inside each row now!
				$queryresult[] = array(
				'id' 			=> $row->id,
				'user_id' 		=> $row->users_id,
				'environment' 	=> $row->environment,
				'gamedata_id' 	=> $row->gamedata_id,
				'message' 		=> $row->message,
				);
			
			}
			
			// echo "All lines of chat in environment";
			return $queryresult;			
			
		}else{
			log_message('error','No chat data available');
			$this->errors = array(
				'database'	=> 'No chat data available.',
			);
			// echo 'No chat data available';
			
			return false;
		}
	}
	
	public function get_errors(){
		return $this->errors;
	}
}