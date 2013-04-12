<?php

//User Account Controller- Create, Read, Update and Delete user accounts

class UserAccount extends CI_Controller{

	//always executed method
	public function __construct(){
		parent::__construct();
		
		$this->load->model('logindata_model');
	}
	
	//default method
	public function index(){
		echo 'Controller page for User Accounts';
	}

	//new methods
	public function newuser(){	//this creates a new account
		$result = $this->logindata_model->create('rosalinetayyufen@gmail.com','ros_tay','123456');
		echo $result;
	}
	
	public function userinfo(){	//this queries user info for a particular userid
		$result = $this->logindata_model->read(1);
		var_dump ($result);
	}
	
	public function alluserinfo(){
		$result = $this->logindata_model->read_all();
		var_dump($result);
	}
	
	public function updateuser(){	//this updates user info for a previously existing userid
		$updateddata=array(	
		'email' => 'timweezk@gmail.com',
		);
	
		$result = $this->logindata_model->update(1,$updateddata);
		var_dump($result);
	}
	
	public function deleteuser(){	//this deletes user info for a userid
		$result = $this->logindata_model->delete(5);
		echo $result;
	}
}