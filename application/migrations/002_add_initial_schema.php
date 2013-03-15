<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Migration_add_initial_schema extends CI_Migration {
    //Added IonAuth in last migration, they have some default tables
	//Can use some of those tables for our app
	//Have to make sure, in future migrations, not to use a table name same as theirs
    
    public function up(){

        //Table 1) 'rankgroups'		
			//Abstraction of userprofile, similar to the groups table by IonAuth. 
			//will only have x number of preset rows- e.g. VIP, King, Queen, Rook, Bishop, Knight, Pawn
			//each user can only have one rank group
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'name' => array(	//e.g. King, VIP
                    'type' => 'varchar',
                    'constraint' => '20',
                ),
                'description' => array(     //e.g. for VIP - "Full chatting rights"
                    'type' => 'varchar',
					'constraint' => '100',
                ),
            )
        );
        $this->dbforge->create_table('rankgroups'); 
		
		// Dumping data for table 'rankgroups'
		$data = array(
			array(
				'name' => 'Admin',
				'description' => 'Exclusive rights'
			),
			array(
				'name' => 'VIP',
				'description' => 'VIP rights'
			)
		);
		$this->db->insert_batch('rankgroups', $data);
 
        //Table 2) 'userprofiles' 
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'users_id' => array(
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
                    'type' => 'varchar',
					'constraint' => '1500'	//approximately 300 words
                ),
            )
        );
        $this->dbforge->create_table('userprofiles');
        $this->db->query('ALTER TABLE  userprofiles ENGINE = MYISAM');   //use MyISAM for more read-intensive tables

        //Table 3) 'payments'
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'users_id' => array(
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
/*              
				//If want to use invoices system in future
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
        $this->db->query('ALTER TABLE  payments ENGINE = MYISAM');
        
        //Table 4) 'gamedata'
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
				'game_status' => array(		//waiting, started, completed, aborted (use INT to represent each)
					'type' => 'SMALLINT',
					'constraint' => '1',
				),
                'player_white' => array(     //users_id of White player
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'player_black' => array(     //users_id of Black player
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
				'player_white_rating' => array(		//rating of White player just before game - linked to Ratings table
					'type' => 'INT',
					'constraint' => '4',
				),
				'player_black_rating' => array(		//rating of Black player just before game - linked to Ratings table
					'type' => 'INT',
					'constraint' => '4',
				),
				'opening_classification' => array(
					'type' => 'varchar',
					'constraint' =>'30',
				),					
					
                'result' => array(     //1-0, 1/2-1/2, 0-1; or WIN LOSE DRAW; or 1 for WIN, 2 for LOSE, 3 for DRAW
                    'type' => 'INT',
                    'constraint' => '1',
                ),
                'time_control' => array(     //e.g. 3min + 1sec 
                    'type' => 'varchar',
                    'constraint' => '20',
                ),
				'result_reason' => array(	//e.g. flag drop, checkmate, stalemate etc.
					'type' => 'varchar',
					'constraint' => '30',
				),
            )
        );
        $this->dbforge->create_table('gamedata');        

        //Table 5) 'movelogs'	NOTE: Can probably find a premade one on php Chess
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'gamedata_id' => array(
                    'type' => 'INT',
                    'constraint' => '9',
                ),
                'color_mover' => array(   //0 for White, 1 for Black. Note: This might not be neccessary.
                    'type' => 'TINYINT',
                ),
                'move_notation' => array(   //e4 or Nf3 or Nc6
                    'type' => 'VARCHAR',
                    'constraint' => '10',
                ),
                'time_spent' => array(   //seconds
                    'type' => 'TIME',
                ),               
            )
        );
        $this->dbforge->create_table('movelogs');
		
        //Table 6) 'chatlogs'	
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'timestamp' => array(   //time of message
                    'type' => 'TIMESTAMP',
                ),
                'users_id' => array(   //user who types the message
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
				'environment' => array(		//is the chat in the game session/main chat? Later on when have rooms for chat, need to add a chatroom_id field
					'type' => 'varchar',
					'constraint' => '20',
				),				
                'gamedata_id' => array(		//for chats that correspond to a game session
                    'type' => 'INT',
                    'constraint' => '9',
                ),
                'message' => array(   //the message being typed
                    'type' => 'varchar',
					'constraint' => '1000'	//max 1000 characters - about 200 words
                ), 				
            )
        );
        $this->dbforge->create_table('chatlogs');
		
        //Table 7) 'ratings'	Note: set a default rating, and default k for all users
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'timestamp' => array(   //time of new rating
                    'type' => 'TIMESTAMP',
                ),
                'users_id' => array(   //users_id corresponding to the new rating
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
				'rating' => array(		//new rating after game
					'type' => 'INT',
					'constraint' => '4',
				),
				'k' => array(	//k used in future rating change calculation		
					'type' => 'INT',
					'constraint' => '3',
				),				
            )
        );
        $this->dbforge->create_table('ratings');
		
        //Table 8) 'friends'	Friends List
        $this->dbforge->add_field('id');        
        $this->dbforge->add_field(
            array(
                'main_user' => array(   //users_id of the user to whom this friends list relates
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
                'main_friends' => array(   //users_id of the friends of main_user
                    'type' => 'MEDIUMINT',
                    'constraint' => '8',
                ),
            )
        );
        $this->dbforge->create_table('friends');
				
    }
 
    public function down(){
	
        $this->dbforge->drop_table('rankgroups');	
        $this->dbforge->drop_table('userprofiles');		
     	$this->dbforge->drop_table('payments');
     	$this->dbforge->drop_table('gamedata');
		$this->dbforge->drop_table('movelogs');
		$this->dbforge->drop_table('chatlogs');
		$this->dbforge->drop_table('ratings');
		$this->dbforge->drop_table('friends');
  
    }
 
}