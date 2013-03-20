<?php

namespace Timwee\Validation\Rule;
use Polycademy\Validation\Rule;	

/**
 * Ensure characters are a-z or 0-9 or underscores
 *
 * @author Tim Wee
 **/
class AlphaNumericUnderscore implements \Polycademy\Validation\Rule  	
	{

	/**
	 * Validate this Rule
	 *
	 * @param string Field name
	 * @param string Field value
	 * @param Validator Validator object
	 * @return bool True if rule passes
	 **/
	public function validate($field, $value, $validator) {
		if(empty($value)) return true;
		return (bool) preg_match('/^[a-z0-9_]+$/i', $value);
	} // end func: validate



	/**
	 * Return error message for this Rule
	 *
	 * @param string Field name
	 * @param string Field value
	 * @param Validator Validator object
	 * @return string Error message
	 **/
	public function get_error_message($field, $value, $validator) {
		return $validator->get_label($field) . ' must use just the letters A to Z or numbers 0-9 or underscores';
	} // end func: get_error_message



} // end class: AlphaNumericUnderscore
