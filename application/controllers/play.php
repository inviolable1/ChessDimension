<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller {

	public function __construct(){
	
		parent::__construct();
		
	}
	
	public function index(){
	
		echo 'Resource/Controller Page for Play (Main App Page) <br/>';
		echo 'If you got here, you must have been able to log in!';
		
	}
}