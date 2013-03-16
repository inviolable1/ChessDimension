<?php defined('BASEPATH') OR exit('No direct script access allowed');

//need to ask Roger how to implement this validation - or do we need both this and form_validation (for XSS cleaning?)
use HybridLogic\Validation\Validator;
use HybridLogic\Validation\Rule;

class Sessions extends CI_Controller {

	protected $validator;
	protected $errors;
	
	private $view_data = array();
	
    public function __construct()
    {

		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->validator = new Validator;

    }

	public function login(){
	
		$username = $this->input->post('username');
		$password = $this->input->post('password');
	
		$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[255]|xss_clean');
		
		if($this->form_validation->run() == true){
			
			//validation successful
			if($this->ion_auth->login($username, $password)){
			
				//login successful
				redirect(base_url() . 'play');
			
			}else{
			
				//login not successful
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect(base_url() .  'logintester/loginfailed');
				//redirect($this->input->server('HTTP_REFERER'));
			
			}
		
		}else{
		
			//validation not successful
			$errors = trim(validation_errors()); //there's a bug in set_flashdata which dies when there's newline whitespace, we're just trimming it here to prevent any errors
			$this->session->set_flashdata('message', $errors);
			
			redirect(base_url() . 'logintester/validationfailed');
			//redirect($this->input->server('HTTP_REFERER'));
			
		}
	
	}
	
	public function register() 
    {
    	$username = $this->input->post('username');
		$first_name = $this->input->post('name[first]');
		$last_name = $this->input->post('name[last]');
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$this->form_validation->set_rules('username','Username','trim|required|min_length[5]|max_length[30]|is_unique[users.username]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[255]|xss_clean');

		if($this->form_validation->run() == true){
			
			if($this->ion_auth->register($username, $password)){ //login input is ran to the ionAuth 'login' model & returns a boolean. 
			
				//registration successful
				$this->session->set_flashdata('message2', $this->ion_auth->messages());
				redirect('home');
			}else{
			
				//registration not successful
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect($this->input->server('HTTP_REFERER'));
			}
		
		}else{

			//form validation not successful
			$errors = trim(validation_errors()); //there's a bug in set_flashdata which dies when there's newline whitespace, we're just trimming it here to prevent any errors
			$this->session->set_flashdata('message', $errors);
			redirect($this->input->server('HTTP_REFERER'));
		}
    }
}
