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
		
		$data = $this->input->json(false,true);	//false means we want all data (pass into the data array)
		
/*	
	//Note: we will use RestClient to test sending JSON data to our database 
		$users_id = $this->input->post('users_id');
		$environment = $this->input->post('environment');
		$gamedata_id = $this->input->post('gamedata_id');
		$message = $this->input->post('message');
		
		//create new message	- this would actually be in a form
		//$users_id = 4605085;	
		// $environment = 'main';
		// $gamedata_id = '';
		// $message = 'i\'m back! why did you keep talking to yourself? Did you miss me!';

		$data = array(
			'users_id' => $users_id,
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
			'message'	=> $message,
		);
		
		//var_dump($data);
*/

		$data = input_message_mapper($data);	//change input data from camelcase (JS) to snakecase (for PHP)
		$result = $this->Chat_model->create($data);
		
		if($result){
			$output = array(
				'status'		=> 'Created',
				'resourceId'	=> $result,
			);
		}else{
			$this->output->set_status_header('400');
			$output = array(
				'error'			=> output_message_mapper($this->Chat_model->get_errors()),
			);
		}
		
		Template::compose(false, $output, 'json');

	}
	
	public function show($environment,$gamedata_id) {		
	
		//read last message in environment and if applicable gamedata_id 
		//http://blog.mashupsdev.com/codeigniter-get-the-last-row-of-table/

		$data = array(
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
		);
		
		$result = $this->Chat_model->read_last($data);
		
		if($result){
			$output = output_message_mapper($result);
		}else{
			$this->output->set_status_header('404');
			$output = array(
				'error'			=> output_message_mapper($this->Chat_model->get_errors()),
			);
		}
		
		Template::compose(false, $output, 'json');
		
	}
	
	public function showEnv($environment,$gamedata_id) {	
		//Roger says - it's not a true read all if it doesnt read literally EVERYTHING
		//read all messages in environment and if applicable gamedata_id
		
		$data = array(
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
		);
		
		$result = $this->Chat_model->read_all($data);

		if($result){
		
			//$output = output_message_mapper($result);		//ask Roger - the output_message_mapper does not seem to work with multidimensional arrays
			$output = $result;	//without changing snake_case to camelCase.
			
		}else{
			$this->output->set_status_header('404');
			$output = array(
				'error'			=> output_message_mapper($this->Chat_model->get_errors()),
			);
		}
		
		Template::compose(false, $output, 'json');
		
	}
	
	protected function authenticated(){
		//check if person was authenticated - Need to write this!
	}
}