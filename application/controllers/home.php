<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	
	//you can build up your view binding by creating a privately scoped member array, when you're ready to build up the view, just append values onto this array and pass this to the view. By creating this independent from any methods, the view data is then abstracted and can be added to from multiple methods
	private $view_data = array();
	
	//since it is a class that extends the CI_Controller, we still have a __construct that is called as soon as it is initiated
    public function __construct(){
	
        parent::__construct();
        $this->load->library('session');
		//abstracted the commonly shared view data to the site_config.php file that is being autoloaded
		$this->view_data += $this->config->item('view_data','chessdimension');
 
        //load other commonly used dependencies using CI, you would not dependency inject using controllers, because you don't control the calling of controllers
    }
	
	public function index() {
	
	//Checks to see if already logged in, if he is direct him to play page.
		//BUT what if he is logged in but wants to access information from the front page?
	if($this->ion_auth->logged_in()){
		redirect(base_url() . 'play');
	}
	
	$this->view_data ['header'] += array(
		'form_destination_login'	=> base_url() . 'sessions/login',
		'form_destination_register'	=> base_url() . 'sessions/register',		
	);	
	
	//testing of firebug logging
	$some_dynamic_variable = 'Hello!';	//so u know what is going on in your variable without affecting the code execution
	
		FB::log($some_dynamic_variable, 'Some logging examples');	//1st argument is the thing being logged, second is label
		FB::warn('This is a warning', 'Warning');
		FB::info('This is information', 'Information');
		
		Template::compose('index', $this->view_data);
		
	}
	
	//just testing restclient - very cool extraction of FB posts
	public function test_spark(){
	
		$this->load->spark('restclient/2.1.0');	//restclient is a spark package
		$this->load->library('rest');	//library in the restclient
		$this->rest->initialize(array('server' => 'http://pipes.yahoo.com/'));	//initialize is a method of the rest library
		$fbposts = $this->rest->get('pipes/pipe.run?_id=5b00d2bfd7146a7c9a049ac355f61bc4&_render=rss');

		var_dump($fbposts);

	}
	
	//testing of shell commands
	public function test_shell(){
		//this particular shell command is pretty simple, in production you'll probably run some program such as a PHP script, bash script, or command line application. Remember if you're using an executable alias, you need to add them to your PATH variables, otherwise you need to absolute path.
 
		//determines if the string "Windows" exists in php_uname() function
		if(stripos(php_uname(), 'windows') !== false){
			//we use the windows dir command
			$cmd = 'dir';
		}else{
			//we use the unix ls command (for Linux and Mac)
			$cmd = 'ls';
		}
		 
		$output = array(); //empty array (exec will append to the array, not overwrite it)
		$return_value = ''; //empty string
		 
		//this would echo out the LAST LINE of the output that would exist if you did this in the command line
		echo exec($cmd, $output, $return_value);
		 
		//this would dump all of output lines as an array
		var_dump($output);
		 
		//this should dump out the integer 0, or else there'd be an error!
		var_dump($return_value);
	}
}


/* End of file home.php */
/* Location: ./application/controllers/home.php */