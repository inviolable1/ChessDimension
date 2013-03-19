<?php

class MY_Input extends CI_Input{

	//extract json input data from input stream
	public function json($index = false, $xss_clean = false, $return_as_object = false){

		if($return_as_object){
			$input_data = json_decode(trim(file_get_contents('php://input')));
		}else{
			$input_data = json_decode(trim(file_get_contents('php://input')), true);
		}

		if($xss_clean){
			foreach($input_data as &$value){
				$value = $this->security->xss_clean($value);
			}
		}

		if($index){
			return $input_data[$index];
		}

		return $input_data;

	}

}