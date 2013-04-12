<?php

//refer to polycademy2/application/models/courses_model.php model and courses.php controller

class RestfulDemo extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('Rest_model');
		echo 'Blah';
	}
	
	/**
	 * Gets all Itesm
	 *
	 * @queryparam int Limit - limit the number of items
	 * @queryparam int Offset - offset the number of items
	 * @return JSON
	 **/

	public function index() {
		
		$data = array(
		
		);
		
		//gets me the limit parameter	(from query string)
		$limit = $this->input->get('limit',true);	//true refers to XSS filter on
		//gets me the offset parameter	(from query string)
		$offset = $this->input->get('offset',true);
		
		$query = $this->Rest_model->read_all($limit, $offset);
		
		if($query){

			foreach($query as &$course){
				$course = output_message_mapper($course);
			}
			$output = $query;

			//$output = $query;
		
		}else{
			
			$this->output->set_status_header('404');
			
			/*
			$output = array(
				'error' => output_message_mapper($this->Rest_model->get_errors()),
			);
			*/
			
			$output = array(
				'error' => 'Oh no something went wrong',
			);
		}
			
		//var_dump($query);
		
		Template::compose(false,$output,'json');
	
	}
	
	public function create(){}
	
	public function show($id){}
	
	public function update($id){}
	
	public function delete($id){}

}