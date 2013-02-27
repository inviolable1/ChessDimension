<?php

//demo in class

class Test extends CI_Controller{

	//always executed method
	public function __construct(){
		parent::__construct();
		
		$this->load->model('chessdata_model');
	}
	
	//default method
	public function index(){
		echo 'I\'m the test page!';
	}

	//new method
	public function checkmate(){
		$checkmate = $this->chessdata_model->read_all();
		var_dump($checkmate);
	}
	
	public function make_new_checkmate(){
		$result = $this->chessdata_model->create('phuong');
		var_dump($result);
	}
}