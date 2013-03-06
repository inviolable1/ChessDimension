<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_initial_schema extends CI_Migration {
    
    public function up(){
 
        //1) Table 'accessgroups' - segment users by access status (ABSTRACT of users)
        $this->dbforge->add_field('id');   
        
        $this->dbforge->add_field(
            array(
                'PermissionGroup' => array( //Segments users into Admin, VIP Member, Premium Member, Member, Guest.
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                ),
            )             
        );
 
        $this->dbforge->create_table('accessgroups');    
 
        //2) Table 'users' - for key user data. (Other less important user data like profile/description/image will be put in another table.)
        $this->dbforge->add_field('id');
        
        $this->dbforge->add_field(
            array(
                'FirstName' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '40',
                ),
                'LastName' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '40',
                ),
                'Email' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '70'
                ),
                'Username' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '30',
                ),
                'Password' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '256',  //because we are encrypting password using hash
                ),
                'AccessGroupsId' => array(
                    'type' => 'INT',
                    'constraint' => 9,                    
                ),
            )
        );
                    
        $this->dbforge->create_table('users');
            
    }
 
    public function down(){
 
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('accessgroups');
 
    }
 
}