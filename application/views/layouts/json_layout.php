<?php defined('BASEPATH') OR exit('No direct script access allowed');

header('Content-type: application/json');

echo json_encode($yield, JSON_NUMERIC_CHECK);	//JSON_NUMERIC_CHECK will change anything that looks like a number to a number type.
