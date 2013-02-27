<?php
	
class Chessdata_model extends CI_model{
	public function __construct(){
		parent::__construct();
		//$this->load->database(); this is not required because we use autoload
	}
	
	public function read($id){
	
	}
	
	public function create ($title){
	
		$data = array(
			'title' => $title,	//key of 'title' (column name from database) and value of $title variable. MUST! so the database knows where the data goes
		);
		
		//insert into the blog table, using the $data array
		$query = $this->db->insert('chessdata',$data);
		
		if(!$query){
		
			$msg = $this->db->_error_message();
			$num = $this->db->_error_number();
			$last_query = $this->db->last_query();
			
			log_message('error','Problem inserting data into the blog table' . $msg . '(' . $num . '), 
			using this query: "' . $last_query . '"');
			
			return false;
		}
		
		return $this->db->insert_id();
	}
	
	public function read_all(){
	
		//Constructs the query
		$this->db->select('*')->from('chessdata');
		
		//MODEL STANDARD: CRUD
		//SQL STANDARD: INSERT SELECT UPDATE DELETE (ISUD)
		//CRUD ==> ISUD
		
		//Executes the query
		$chessdata_result = $this->db->get();
		
		if($chessdata_result->num_rows() > 0){
			
			$chessdata_result = $chessdata_result->result_array();
			
			return $chessdata_result;
			
		}else{
			return false;
		}
	}
}