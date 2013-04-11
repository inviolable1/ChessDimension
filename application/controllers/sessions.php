<?php

use Polycademy\Validation\Validator;

//in this class, $ids refer to the user ids, not the session ids!
class Sessions extends CI_Controller{

	private $validator;

	public function __construct(){
		
		parent::__construct();
		
		$this->load->library('ion_auth');
		$this->load->driver('session');
		$this->validator = new Validator;
	
	}
	
	//give back information about all the user's session (if you're admin)
	public function index(){
	
		if($this->ion_auth->is_admin()){
		
			//show all current sessions (not all current users)	 (must be using sessions tables)
			$query = $this->db->get($this->config->item('sess_table_name'));
			
			if($query->num_rows() > 0){
			
				foreach($query->result() as $row){
				
					//have to unserialise the custom data
					$custom_data = $this->unserialize($row->user_data);
					
					$user_sessions[] = array(
						'type'			=> (!empty($row->user_data)) ? 'member' : 'guest',
						'session_id'	=> $row->session_id,
						'ip_address'	=> $row->ip_address, //ip_address from sessions
						'user_agent'	=> $row->user_agent,
						'last_activity'	=> $row->last_activity,
						'user_data'		=> $custom_data,
					);
					
				}
				
				$output = array(
					'content'	=> $user_sessions,
					'code'		=> 'success',
				);
				
			}else{
			
				$this->output->set_status_header('404');
				$output = array(
					'content'	=> 'No one is currently logged in.',
					'code'		=> 'error',
				);
			
			}
		
		}else{
			
			//unauthorised permission
			$this->output->set_status_header(403);
			
			$output = array(
				'content'	=> 'You don\'t have the authorisation to see all the sessions!',
				'code'		=> 'error',
			);
		
		}
		
		Template::compose(false, $output, 'json');
		
	}
	
	private function unserialize($data){
	
		$data = @unserialize(trim($data));
		if (is_array($data)){
			array_walk_recursive($data, array(&$this, 'unescape_slashes'));
			return $data;
		}

		return is_string($data) ? str_replace('{{slash}}', '\\', $data) : $data;
		
	}
	
	private function unescape_slashes(&$val, $key){
	
		if (is_string($val)){
	 		$val = str_replace('{{slash}}', '\\', $val);
		}
	
	}
	
	//show the the session data relating to user id
	//can only show current person's session, $id is just for REST
	//this is the function that will be utilised at startup!
	public function show($id){
	
		if($id == 0){
		
			//if $id is 0, just grab the current session
			$output = array(
				'content'	=> $this->session->all_userdata(),
				'code'		=> 'success',
			);
			
			$user_data = $this->ion_auth->user()->row();
			
			if(!empty($user_data)){
				$user_id = $user_data->id;
				//we want to store the userId in a different place, as this could overwrite the session id
				$output['content']['userId'] = $user_id;
			}
		
		}else{
		
			//grab a specified session, either the person must own it, or the person is an admin
			//not yet implemented
		
		}
		
		Template::compose(false, $output, 'json');
		
	}
	
	//create a session! used for login
	public function create(){
	
		//only create a new session, if the person is not logged in
		if(!$this->ion_auth->logged_in()){
			
			//check if data is validated
			$data = $this->input->json(false, true);
			
			//THIS depends on the fact that you set the username as the identity field in the config
			$this->validator->setup_rules(array(
				'username'		=> array(
					'set_label:Username',
					'NotEmpty',
					'AlphaNumericSpace',
					'MinLength:4',
					'MaxLength:100',
				),
				'password'		=> array(
					'set_label:Password',
					'NotEmpty',
					'AlphaSlug',
					'MinLength:8',
					'MaxLength:80'
				),
				'rememberMe'	=> array( //<- does not correspond with table column's name
					'set_label:Remember Me',
					'MaxLength:1',
				),
			));
			
			if(!$this->validator->is_valid($data)){
			
				$this->output->set_status_header(400);
				
				$output = array(
					'content'	=> $this->validator->get_errors(),
					'code'		=> 'validation_error',
				);
			
			}else{
			
				//validator passed
				//check if data is authenticated
				
				$remember_me = (isset($data['rememberMe'])) ? (bool) $data['rememberMe'] : false;
				
				if($this->ion_auth->login($data['username'], $data['password'], $remember_me)){
					
					$current_user = $this->ion_auth->user()->row();
					
					//logged in
					$this->output->set_status_header(201);
					
					$output = array(
						'content'	=> $current_user->id,
						'code'		=> 'success',
					);
					
				}else{
				
					//not logged in
					$this->output->set_status_header(400); //fudged, make it a 400 code, cant use 401, and cant use 403 due to redirection possibility
					
					$output = array(
						'content'	=> $this->ion_auth->errors_array(),
						'code'		=> 'validation_error',
					);
				
				}
				
			}
			
		}else{
		
			//if the person is already logged in, then no need to do it
			//return the resource ID of the current user
			$current_user = $this->ion_auth->user()->row();
			
			$this->output->set_status_header(200);
			
			$output = array(
				'content'	=> $current_user->id,
				'code'		=> 'success',
			);
		
		}
		
		Template::compose(false, $output, 'json');
	
	}
	
	//not implemented yet (possibly for shopping cart)
	//$id should be the user id, session id is encrypted
	public function update($id){
		return false;
	}
	
	//used to delete a session
	//logout only works for the person who is logged in, you cannot log somebody else out!
	//$id is only for REST currently
	public function delete($id){
	
		//only delete if the person is logged in
		if($this->ion_auth->logged_in()){
			
			$current_user = $this->ion_auth->user()->row();
			
			$this->ion_auth->logout();
			
			$output = array(
				'content'	=> $current_user->id,
				'code'		=> 'success',
			);
			
			//this function should check for 0, to logout the current person, if not 0, logout a particular person...
		
		}else{
			
			//no resource to delete
			$this->output->set_status_header(200);
			
			$output = array(
				'content'	=> 'You cannot log out when you are not logged in.',
				'code'		=> 'error',
			);
		
		}
		
		Template::compose(false, $output, 'json');
		
	}

}