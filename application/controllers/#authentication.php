<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {

	//all the result pages of the authentication are methods here
	
	public function __construct() {
	
		parent::__construct();
		
	}
	
	public function index(){
	
		echo 'Checker Page for Authentication <br/>';
		echo 'I check results of session authentication!';
	
	}
	
	public function loginFailed(){
		
		echo 'Login failed - Passed validation but incorrect user name or password <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
	
	public function loginValidationFailed(){
		
		echo 'Login Validation failed <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}

	public function registerFailed(){
		
		echo 'Registration failed - Passed validation but rejected by IonAuth <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
	public function registerValidationFailed(){
		
		echo 'Register Validation failed <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
	
	public function registrationSuccessful(){
		
		echo 'Registration Successful! <br/><br/>';
		if($this->session->flashdata('message')!=''){
			echo 'Session FlashData Message:';
			echo $this->session->flashdata('message');
		}
	}
	
	public function loggedout(){
		
		echo 'You have been logged out. Thank you for playing at Chess Dimension!';

	}
}