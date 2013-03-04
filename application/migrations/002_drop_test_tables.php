<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
//notice that the name of the class will be Migration_add_missions whereas the file name is 001_add_missions
class Migration_drop_test_tables extends CI_Migration {
 
    public function up(){
 
        $this->dbforge->drop_table('missions');
    }
 
    public function down(){
 
        $this->dbforge->add_field('id');
 
        $this->dbforge->add_field(
            array(
                'title' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                ),
                'description' => array(
                    'type' => 'TEXT',
                ),
                'parameters' => array(
                    'type' => 'TEXT',
                ),
            )
        );
 
        $this->dbforge->create_table('missions');
    }
}