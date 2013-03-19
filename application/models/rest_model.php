<?php

class Rest_model extends CI_Model{

	public $dummy_data;
	
	public function __construct(){
	
		parent::__construct();
		
		//here's some dummy data, that would actually be in the database...
		$this->dummy_data = array(
			array(
				'id' => '2',
				'name' => 'Roger', 
				'power' => 'Over 10,000!',
			),
			array(
				'id' => '3',
				'name' => 'Murray', 
				'power' => 'Over 10,000!',
			),
			array(
				'id' => '4',
				'name' => 'Nadal', 
				'power' => 'Over 10,000!',
			),
			array(
				'id' => '5',
				'name' => 'Joker', 
				'power' => 'Over 10,000!',
			),
			array(
				'id' => '6',
				'name' => 'Tim', 
				'power' => 'Over 10,000!',
			),
		);
	}
	
	public function read_all(){
	
		//YOU would need to do db->select everything, then find out how many there is, and then iterate through it and return it.
		foreach($this->dummy_data as $row){
		
			$data[] = array(
				'id' => $row['id'],
				'name' => $row['name'],
				'power_magic' => $row['power'],
			);
		};
		
		return $data;
	}
}