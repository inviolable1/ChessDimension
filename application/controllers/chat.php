<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

	private $view_data = array();
	
	public function __construct(){
	
		parent::__construct();
		$this->load->model('Chat_model');
	
	}
	
	//SHOW ALL CHAT MESSAGES
	public function index(){	//should include read_all here if needed
		
	}
	
	//Only required to be able to create new messages, and read messages. No need for update/delete. 
	
	public function create() {	
		//note: either use post or 	<?= form_open($form_destination) in view
	
		$this->authenticated();	//person has to be logged in to chat
		
		$data = $this->input->json(false,true);	//false means we want all data (pass into the data array). True means XSS filtering is on.
		
/*	
	//Note: we will use RestClient to test sending JSON data to our database 
		$usersId = $this->input->post('usersId');
		$environment = $this->input->post('environment');
		$gamedataId = $this->input->post('gamedataId');
		$message = $this->input->post('message');
		
		//create new message	- this would actually be in a form
		//$usersId = 4605085;	
		// $environment = 'main';
		// $gamedataId = '';
		// $message = 'i\'m back! why did you keep talking to yourself? Did you miss me!';

		$data = array(
			'usersId' => $usersId,
			'environment' => $environment,
			'gamedataId' => $gamedataId,
			'message'	=> $message,
		);
		
		//var_dump($data);
*/

		$result = $this->Chat_model->create($data);
		
		if($result){
		
			$this->output->set_status_header('201');
			$content = $result; 
			$code = 'success';
			
		}else{
			
			$content = current($this->Chat_model->get_errors());	//current gets the values of the get_errors array
			$code = key($this->Chat_model->get_errors());		//key gets the keys of the get_errors array
			
			if($code == 'validation_error'){
				$this->output->set_status_header(400);
			}elseif($code == 'system_error'){
				$this->output->set_status_header(500);
			}
			
		}

		$output = array(
			'content'	=> $content,
			'code'		=> $code,
		);
		
		Template::compose(false, $output, 'json');

	}
	
	public function show($environment,$gamedataId) {		
	
		//read last message in environment and if applicable gamedataId 
		//http://blog.mashupsdev.com/codeigniter-get-the-last-row-of-table/

		$data = array(
			'environment' => $environment,
			'gamedataId' => $gamedataId,
		);
		
		$result = $this->Chat_model->read_last($data);
		
		if($result){

			$content = $result;
			$code = 'success';
			
		}else{
			
			$this->output->set_status_header('404');
			$content = current($this->Chat_model->get_errors());
			$code = key($this->Chat_model->get_errors());
			
		}

		$output = array(
			'content'	=> $content,
			'code'		=> $code,
		);
		
		Template::compose(false, $output, 'json');
		
	}
	
	public function showEnv($environment,$gamedataId) {	
		//Roger says - it's not a true read all if it doesnt read literally EVERYTHING
		//read all messages in environment and if applicable gamedataId
		
		$data = array(
			'environment' => $environment,
			'gamedataId' => $gamedataId,
		);
		
		$result = $this->Chat_model->read_all($data);

		if($result){

			$content = $result;
			$code = 'success';
			
		}else{
			
			$this->output->set_status_header('404');
			$content = current($this->Chat_model->get_errors());
			$code = key($this->Chat_model->get_errors());
			
		}

		$output = array(
			'content'	=> $content,
			'code'		=> $code,
		);
		
		Template::compose(false, $output, 'json');
		
	}
	
	protected function authenticated(){
		//check if person was authenticated - Need to write this!
	}
}