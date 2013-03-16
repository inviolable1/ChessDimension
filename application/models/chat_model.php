<?php

use HybridLogic\Validation\Validator;
use HybridLogic\Validation\Rule;
	
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
		
		//Labels
		$this->validator
			->set_label('users_id', 'User ID')
			->set_label('enviroment', 'Chat Environment')
			->set_label('message', 'Chat Message');
		
		//users_id rules
		$this->validator
			->add_rule('users_id', new Rule\NotEmpty())
			->add_rule('users_id', new Rule\Number())
			->add_rule('users_id', new Rule\MaxLength(9));
		
		//environment rules
		$this->validator
			->add_rule('environment', new Rule\NotEmpty())
			->add_rule('environment', new Rule\MaxLength(10));
			
		//message rules
		$this->validator
			->add_rule('message', new Rule\NotEmpty())
			->add_rule('message', new Rule\MaxLength(1000));
		
		if(!$this->validator->is_valid($data)){
		
			//returns array of key for data and value
			$this->errors = $this->validator->get_errors();
			log_message('error','Validation failed');
			echo 'Validation Failed <br/>';
			print_r($this->errors);
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
 
			print_r($this->errors);
            return false;
			
        }
		
		echo 'Chat saved! The chat message has an ID of ';
        return $this->db->insert_id();
		
	}
	
	public function read_last($data) {
	
		$this->db->select('*');
		$this->db->from('chatlogs');
		//http://stackoverflow.com/questions/9941566/codeigniter-select-query-with-and-and-or-condition shows how to add multiple where functions
		
		if($data['gamedata_id'] =='0'){
			$this->db->where('environment',$data['environment']);
		}else{
			$this->db->where('gamedata_id',$data['gamedata_id']);
		}
		
		$query = $this->db->get(); 
	
		if($query->num_rows() > 0){
			$query = $query->last_row();
			echo "Last line of chat in environment";
			return $query;
		}else{
			log_message('error','No chat data available');
			echo 'No chat data available';
			return false;
		}
	}
	
	public function read_all($data) {
	
		$this->db->select('*');
		$this->db->from('chatlogs');
		
		if($data['gamedata_id'] =='0'){
			$this->db->where('environment',$data['environment']);
		}else{
			$this->db->where('gamedata_id',$data['gamedata_id']);
		}
		
		$query = $this->db->get(); 

		if($query->num_rows() > 0){
			$query = $query->result_array();
			echo "All lines of chat in environment";
			return $query;			
		}else{
			log_message('error','No chat data available');
			echo 'No chat data available';
			return false;
		}
	}
}