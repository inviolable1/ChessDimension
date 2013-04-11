<?php defined('BASEPATH') OR exit('No direct script access allowed');

//Note: XSS filtering can be done seperately when getting input
use Polycademy\Validation\Validator;
use Polycademy\Validation\Rule;
use Timwee\Validation\Rule\AlphaNumericUnderscore;

class Sessions extends CI_Controller {

	protected $validator;
	protected $errors;
	protected $messages;
	
	private $view_data = array();
	
    public function __construct()
    {

		parent::__construct();
		$this->load->library('session');
		$this->validator = new Validator;

    }

	public function login(){
	
		$data = $this->input->json(false,true);	//this function uses the custom Input library in Core
	
		$this->validator
				->add_filter('username', 'trim')	//Note: Trim is a default PHP function, not in library. 
				->add_filter('password', 'trim');
				
		/*	NOTE! Spaces after your last character are removed, but not whitespace in middle. 
			The validator checks if the data is valid AFTER the trimming
			However, when it comes to the IonAuth login, what is being passed through is the untrimmed data.
			Hence if I type 'inviolable 1' it will give Validation Error
			If I type 'inviolable1 ' it will pass Validation, but give Login Error because it is wrong username!
		*/
		
		$this->validator->add_rule('username',new Timwee\Validation\Rule\AlphaNumericUnderscore);
		$this->validator->setup_rules(array(
			'username' => array(
				'set_label:Username',
				'NotEmpty',
				'MaxLength:100',
			),
			'password' => array(
				'set_label:Password',
				'NotEmpty',
			),
		));

		if(!$this->validator->is_valid($data)){
			
			$this->errors = array(
				'validation_error'	=> $this->validator->errors,
			);		
			
			$this->output->set_status_header(400);
			
			$content = current($this->errors);	
			$code = key($this->errors);		
			
		}else{		
			//validation successful, so try and login
			if($this->ion_auth->login($data['username'],$data['password'])){

				//login successful
				$this->output->set_status_header('201');	//201 for create. successful creating of a session
				$result = $this->ion_auth->user()	//user ID
				$content = $result; 	
				$code = 'success';
					
			}else{
	
				//login not successful
				$this->errors = array(
					'system_error'	=> $this->ion_auth->errors(),
				);				
				
				$this->output->set_status_header('403');	//Session based cookie authentication failure

				$content = current($this->errors);	
				$code = key($this->errors);	
				
			};
		}	
	
		$output = array(
			'content'	=> $content,
			'code'		=> $code,
		);
				
		Template::compose(false, $output, 'json');
		
	}
	
	public function register() 	//Start working on this and others (use format from login method)
    {
	
		//problem with this currently is that the first name and last name doesn't get passed through to the database.

		$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[30]|alpha_underscore|is_unique[users.username]|xss_clean');
		$this->form_validation->set_rules('firstName', 'First Name', 'trim|required|max_length[30]|alpha|xss_clean');
		$this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|max_length[30]|alpha|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[30]|alpha_numeric|xss_clean');

		if($this->form_validation->run() == true){

			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$first_name = $this->input->post('firstName');
			$last_name = $this->input->post('lastName');
			
			$additional_data = array(
				'firstName' => $first_name,
				'lastName'  => $last_name,
			);
			
			if($this->ion_auth->register($username, $password, $email, $additional_data)){ //login input is ran to the ionAuth 'login' model & returns a boolean. 
			
				//registration successful
				
				//automatically log him in after registration
				$this->ion_auth->login($username, $password);
				
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect(base_url() . 'authentication/registrationsuccessful');
			
			}else{
			
				//registration not successful
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect(base_url() . 'authentication/registerfailed');
				//redirect($this->input->server('HTTP_REFERER'));
			}
		
		}else{

			//form validation not successful
			$errors = trim(validation_errors()); //there's a bug in set_flashdata which dies when there's newline whitespace, we're just trimming it here to prevent any errors
			$this->session->set_flashdata('message', $errors);
			redirect(base_url() .  'authentication/registervalidationfailed');
			//redirect($this->input->server('HTTP_REFERER'));
		}
    }
	
	public function logout(){
	
		$this->ion_auth->logout();
		redirect(base_url() .  'authentication/loggedout');
	
	}
}
