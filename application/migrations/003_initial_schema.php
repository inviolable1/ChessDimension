<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_initial_schema extends CI_Migration {
    
    public function up(){
 
        //1) Table 'users' - for key user data. (Other less important user data like profile/description/image will be put in another table.)
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
            )
        );
                    
        $this->dbforge->create_table('users');
            
        //2) Table 'usergroups' - to segment users into user groups.
        $this->dbforge->add_field('id');    //ask: how do i make only a few group IDs - for each corresponding group name? then many users.id in one group ID. This current id will auto increment- not what I want.
        
        $this->dbforge->add_field(
            array(
                'Users.id' => array(
                    'type' => 'INT',
                    'constraint' => 9,
                ),
                'PermissionGroup' => array( //Segments users into Admin, VIP Member, Premium Member, Member, Guest.
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                ),
                'RankGroup' => array(   //like Playchess.com or Stack Overflow - different rights for Rook/Knight/Queen status.
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                ),
            )             
        );
 
        $this->dbforge->create_table('usergroups');
		
		
    
    }
 
    public function down(){
 
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('groups');
 
    }
 
}