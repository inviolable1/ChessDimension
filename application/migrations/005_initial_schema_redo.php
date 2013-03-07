<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_initial_schema_redo extends CI_Migration {
    //because added IonAuth, they have some default tables. We can use some of those tables, also we have to make share not to use a table name same as theirs.
    
    public function up(){
 
        //we do not need accessgroups table - we can use 'groups' table by IonAuth. same for 'users' table 
        $this->dbforge->drop_table('accessgroups');  
 
        //Table 1) 'userinfo' - for key user data. (Other less important user data like profile/description/image will be put in another table.)
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
 
 
 
 
    }
 
}