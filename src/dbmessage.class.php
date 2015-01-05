<?php 
include_once 'db.class.php';

class dbteam {

	protected $db;
	
	public function __construct(){
		$this->db = database::getInstance()->dbc;
	}

	public function loadMessage($messagecode) {
		$query = $this->db->prepare("select messagetext from message where messagecode = :messagecode");
		$query->execute(array('messagecode' => $messagecode);
		$message = $query->fetch(PDO::FETCH_OBJ);		
		return $message;
	}
	
}
