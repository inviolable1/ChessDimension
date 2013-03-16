<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

	public function __construct(){
	
		parent::__construct();
		$this->load->model('Chat_model');
		
	}
	
	public function index(){
	
		echo 'Resource/Controller Page for Chat';
		
	}
	
	//Only required to be able to create new messages, and read messages. No need for update/delete. 
	//Alternatively, if no need to save messages, can use Ross' suggestion and just update a field and read that.
	
	public function newMessage() {	
		
		//create new message
		$users_id = 4581068;	
		$environment = 'main';
		$gamedata_id = '';
		$message = 'are you still there? well bye!';
		
		$data = array(
			'users_id' => $users_id,
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
			'message'	=> $message,
		);
		
		$result = $this->Chat_model->create($data);
		echo $result;
		
	}
	
	public function readLastMessage() {	
	
		//read last message in environment and if applicable gamedata_id
			//http://blog.mashupsdev.com/codeigniter-get-the-last-row-of-table/
		$environment = 'game';
		$gamedata_id = '';
		
		$data = array(
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
		);
		
		$result = $this->Chat_model->read_last($data);
		var_dump ($result);
		
	}
	
	public function readAllMessage() {	
	
		//read all messages in environment and if applicable gamedata_id
		$environment = 'main';
		$gamedata_id = '';
		
		$data = array(
			'environment' => $environment,
			'gamedata_id' => $gamedata_id,
		);
		
		$result = $this->Chat_model->read_all($data);
		var_dump ($result);
		
	}
}