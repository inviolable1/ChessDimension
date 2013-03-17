<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//My custom form validation rules
class MY_Form_validation extends CI_Form_validation {

	public function __construct() {
	    parent::__construct();
	}
	
	public function alpha_underscore($str)
    {
		return (bool) preg_match('/^[a-z0-9_]+$/i', $str);
	}
}
