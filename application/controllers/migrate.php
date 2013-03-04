<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migrate extends CI_Controller {
 
    public function __construct(){
 
        parent::__construct();
        $this->load->library('migration');
 
    }
 
    public function index(){
	
		echo 'Latest Migration';
		
        //this code means we're always going to get the latest migrations, that's why we didn't need to worry about migration_version in the migration config.
        if(!$this->migration->latest()){	//this $this->migration->latest() is actually a command to update (UP function), if it fails to update, then !$this = TRUE, then return error
            show_error($this->migration->error_string());
		}
 
    }
	
	public function revert($num){
		//e.g. if we use revert(4) when our current version is 7. We will revert back to version 4. 
		//This will carry out down(5),down(6),down(7).
		//**NOTE: we will not want revert in our production- otherwise someone could revert your database schema!
		if(!$this->migration->version($num)){
			show_error($this->migration->error_string());
		}
	
	}
 
}