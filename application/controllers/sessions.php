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
	
		$data = $this->input->json(false,true);	
		// $username = $this->input->post('username');
		// $password = $this->input->post('password');
		
		$data = input_message_mapper($data);	
	
		// $this->form_validation->set_rules('username','Username','trim|required|xss_clean');
		// $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
		
		$this->validator
				->add_filter('username', 'trim')	//Note: Trim is a default PHP function, not in library
				->add_filter('password', 'trim');
		
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
		
			$this->output->set_status_header('400');
			
			$this->errors = $this->validator->errors;
			$output = array(
				'error' => output_message_mapper($this->errors),
			);
			// return false;
		}
		
		//validation successful, so try and login
		if($this->ion_auth->login($data['username'],$data['password'])){
		
			//login successful
			$output = array(
				'status' => 'successful login'
			);
				
		}else{
		
			//login not successful
			$this->output->set_status_header('400');
			
			$this->errors = $this->ion_auth->errors();
			$output = array(
				'error' => output_message_mapper($this->errors),
			);
		
		}	
				
		Template::compose(false, $output, 'json');
		
	}
	
	public function register() 
    {
	
		//problem with this currently is that the first name and last name doesn't get passed through to the database.

		$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[30]|alpha_underscore|is_unique[users.username]|xss_clean');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[30]|alpha|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[30]|alpha|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[30]|alpha_numeric|xss_clean');

		if($this->form_validation->run() == true){

			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$first_name = $this->input->post('first_name');
			$last_name = $this->input->post('last_name');
			
			$additional_data = array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
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
