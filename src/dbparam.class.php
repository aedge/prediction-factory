<?php 
include_once '/prediction/src/db.class.php';

class dbparam {
	
	static function loadParametersBySection($section){
		global $db;
		$query = $db->prepare("select * from parameter where section = :section");
		$query->execute(array("section" => $section));
		$params = $query->fetchAll(PDO::FETCH_OBJ);
		
		return $params;
	}	
}
	