<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_initial_schema_redo extends CI_Migration {
    //because added IonAuth, they have some default tables. We can use some of those tables, also we have to make sure not to use a table name same as theirs.
    
    public function up(){
 
        //we do not need accessgroups table - we can use 'groups' table by IonAuth. same for 'users' table 
        //$this->dbforge->drop_table('accessgroups');  
 
        //Table 1) 'userprofile' 
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'USERS_id' => array(
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'dob' => array(     //ddmmyyyy
                    'type' => 'DATE',
                ),
                'title' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '30',
                ),
                'fide_rating' => array(
                    'type' => 'INT',
                    'constraint' => '4', 
                ),
                'personal_notes' => array(
                    'type' => 'TEXT',
                ),
            )
        );
        $this->dbforge->create_table('userprofile');
        $this->db->query('ALTER TABLE  userprofile ENGINE = MYISAM');   //CHECK IF THIS WORKS

        //Table 2) 'payments'
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'USERS_id' => array(
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'rate_plan' => array(   //cost per payment
                    'type' => 'FLOAT',
                ),
                'time_plan' => array(   //frequency of payment
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                ),
                'status' => array(   //Payment Status - e.g. 1 for active, 0 for inactive
                    'type' => 'TINYINT',
                ),               
                
/*              //If want to use invoices system in future
                'next_invoice' => array(    //Next invoice number/date
                    'type' => 'INT',
                    'constraint' => '8', 
                ),
                'next_invoice_due' => array(    //Due date for invoice
                    'type' => 'DATE',
                    'constraint' => '8',
                ),
*/
            )
        );
        $this->dbforge->create_table('payments');
        
        //Table 3) 'gamedata'
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'player1' => array(     //USERS_id of first player
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'player2' => array(     //USERS_id of first player
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'result' => array(     //1-0, 1/2-1/2, 0-1; or WIN LOSE DRAW; or 1 for WIN, 2 for LOSE, 3 for DRAW
                    'type' => 'INT',
                    'constraint' => '1',
                ),
                'timecontrol' => array(     //e.g. 3min + 1sec 
                    'type' => 'varchar',
                    'constraint' => '20',
                ),
            )
        );
        $this->dbforge->create_table('gamedata');        
        
        
    }
 
    public function down(){
     	$this->dbforge->drop_table('gamedata');
     	$this->dbforge->drop_table('payments');
        $this->dbforge->drop_table('userprofile');
  
    }
 
}