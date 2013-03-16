<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ValidationFailed extends CI_Controller {

	public function index(){
		
		echo 'Validation failed <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
}