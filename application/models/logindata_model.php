<?php
	
class LoginData_model extends CI_model{
	public function __construct(){
		parent::__construct();
		//$this->load->database(); this is not required because we use autoload
	}
	
	//MODEL STANDARD: CRUD
	//SQL STANDARD: INSERT SELECT UPDATE DELETE (ISUD)
	//CRUD ==> ISUD
	
	public function create($email, $username, $password){
	
		$data = array(
			'email'		=> $email,
			'username'	=> $username,
			'password'	=> $password, //should use password hash later on- need to change
		);
		
		$newuser = $this->db->insert('logindata', $data);
		
		if(!$newuser){
			//this means some error occured so we should log the error
			//note: this works when $db['default']['db_debug'] = FALSE in the database config, currently it is set to TRUE
			$msg = $this->db->_error_message();
			$num = $this->db->_error_number();
			$last_query = $this->db->last_query();
			
			log_message('error','Problem inserting data into the blog table' . $msg . '(' . $num . '), 
			using this query: "' . $last_query . '"');
			
			return false;
		}
		
		//otherwise, if it works, return the last insert_id
		echo 'Account creation successful! The account just created has a userid of ';
		return $this->db->insert_id();
	}
		
	public function read ($userid){
	
	    //first validate the $userid somehow
        if(!is_numeric($userid)){
            show_404();
        }
		
		$this->db->select('*');
		$this->db->from('logindata');
		$this->db->where('userid',$userid);
		
		$userinfo = $this->db->get();
		
		if($userinfo->num_rows() > 0){
			$result = $userinfo->row();
			echo "Details of User ID $userid <br />";
			
			return $result;
		}else{
			return false;
		}
	}
	
	public function read_all(){
	
		//Constructs the query
		$this->db->select('*');
		$this->db->from('logindata');
		
		$alluserinfo = $this->db->get();
		
		if($alluserinfo->num_rows() > 0){
			$alluserinfo = $alluserinfo->result_array();
			
			return $alluserinfo;			
		}else{
			return false;
		}
	}

	public function update($userid,$updateddata){
	
		$this->db->where('userid',$userid);
		$this->db->update('logindata',$updateddata);
		
		if($this->db->affected_rows() > 0){
			echo "Account Details for User ID $userid has been updated. <br /> Fields Changed: ";
			foreach(array_keys($updateddata) as $changes)
				echo $changes . ' ';
			echo '<br /> New details as follows: <br />';
			$this->db->select('*');
			$this->db->from('logindata');
			$this->db->where('userid',$userid);
			$changedrow = $this->db->get()->row();
			
			return $changedrow;
		}else{
			return false;
		}
	}
	
	public function delete($userid){
		
		$this->db->where('userid',$userid);
		$deletion = $this->db->delete('logindata');
		
		if($deletion){	//db->delete would return true if it worked
			if($this->db->affected_rows() > 0){
				echo "User ID $userid has been deleted.";
			}else{
				echo "User ID $userid has already been deleted before this.";
			}
			
		}else{
			return false;
		}		
	}
	
}