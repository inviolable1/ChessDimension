<?php

//basically set up your basic stuff yourself, then refer to ion auth to use their functions to do login

class Triallogin extends CI_Controller{

	private $view_data=array();
	
	public function __construct(){
		
		parent::__construct();
		$this->load->library('session');
		$this->load->library('form_validation');
		
		$this->form_validation->set_error_delimiters(
			'<p>',
			'</p>'
		);			
		
		//configure default view data
		
		$this->view_data +=array(
			'header' => array(
				'header_message' => 'This is a header message',
			),
			'footer' => array(
				'footer_message' => 'This is a footer mssage',
			),
		);
	}

	public function index(){
		if(!$this->ion_auth->logged_in()){
			//NOT LOGGED IN
			$this->view_data += array(
				'form_destination' => $this->router->fetch_class() .'/create_new',
			);
			
			Template::compose('index', $this->view_data);
		}else{
			//LOGGED IN
			redirect('home');
		}
			
	}
	
	public function create_new(){
		
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password','Password','required');
		
		if($this->form_validation->run() == true){
		
			$post_data = $this->input->post();
		
			var_dump($post_data);
		}else{
			
			$this->view_data += array(
				'validation_errors' => validation_errors(),
			);
			
			Template::compose('create_new', $this->view_data);
		}
	}
}