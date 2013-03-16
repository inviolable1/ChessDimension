<?php defined('BASEPATH') OR exit('No direct script access allowed');

class LoginFailed extends CI_Controller {

	public function index(){
		
		echo 'Login failed - Passed validation but incorrect user name or password <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
}