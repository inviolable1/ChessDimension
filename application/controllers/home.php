<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2013, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/home
	 *	- or -
	 * 		http://example.com/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	
	//you can build up your view binding by creating a privately scoped member array, when you're ready to build up the view, just append values onto this array and pass this to the view. By creating this independent from any methods, the view data is then abstracted and can be added to from multiple methods
	private $_view_data = array();
	
	//since it is a class that extends the CI_Controller, we still have a __construct that is called as soon as it is initiated
    public function __construct(){
        parent::__construct();
 
        //load commonly used dependencies using CI, you would not dependency inject using controllers, because you don't control the calling of controllers
    }
	
	//this is the actual method that would be called if there was no second URL segment, it can also be explicitly called by http://example.com/home/index
    //to be callable by URL, they would have to be scoped at public
	public function index()
	{
		$some_dynamic_variable = 'Hello!';
		
		FB::log($some_dynamic_variable, 'Some logging examples');	//1st argument is the thing being logged, second is label
		FB::warn('This is a warning', 'Warning');
		FB::info('This is information', 'Information');
		
        $view_data = array(
			'header' => array(),
			'footer' => array(),
		);
		
		Template::compose('index', $view_data);
	}
	
	public function test_spark(){
		$this->load->spark('restclient/2.1.0');	//restclient is a spark package
		$this->load->library('rest');	//library in the restclient
		$this->rest->initialize(array('server' => 'http://pipes.yahoo.com/'));	//initialize is a method of the rest library
		$fbposts = $this->rest->get('pipes/pipe.run?_id=5b00d2bfd7146a7c9a049ac355f61bc4&_render=rss');

		var_dump($fbposts);
		
		//*REMEMBER TO SET XE DEBUG SETTINGS ACCORDING TO SOLUTION STACK
	}
	
	public function examplehome()
	{
		$this->load->view('examplehome_view',$this->_view_data);
	}
		
	public function furtherlink(){	//accessible by http://localhost/ChessDimension/index.php/home/furtherlink
	echo 'furtherlink';
	}
}


/* End of file home.php */
/* Location: ./application/controllers/home.php */