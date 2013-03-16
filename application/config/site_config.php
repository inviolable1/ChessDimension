<?php


//abstract the commonly shared view data to the site_config.php file that is being autoloaded
//go to autoload, and add site_config.php
//can refer to it from $this->config->item('view_data')



$config['chessdimension'] = array(
	'view_data' => array(
		'header' => array(),
		'footer' => array(),		
	),
);

