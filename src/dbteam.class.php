<?php 
include_once '/home/andrewed/public_html/prediction/src/db.class.php';

class dbteam {

	protected $db;
	
	public function __construct(){
		$this->db = database::getInstance()->dbc;
	}

	public function loadTeamsByCompetition($compid) {
		$query = $this->db->prepare("select teamid, name from team where competitionid = :compid order by name");
		$query->execute(array('compid' => $compid));
		$results = $query->fetchAll(PDO::FETCH_OBJ);		
		return $results;
	}
	
}