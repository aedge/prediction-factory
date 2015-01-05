<?php
include_once 'dbgame.class.php';

$reqAccessLevel = 5;

if ((include 'checklogin.php') != 'OK') {
	exit('invalid login');
}

$competitionid = $_POST["competitionid"];

if($competitionid != 0){

	$competition = dbgame::loadCompetition($competitionid);	
	$gameDate  = $_POST["gameDate"];
	$gameSplit = $_POST["gameSplit"];
	$gameTeam1 = $_POST["gameTeam1"];
	$gameTeam2 = $_POST["gameTeam2"];	
	$week 	   = 0;
	$game 	   = "";
	$error     = "";
	
	if($gameDate == ""){
		$error = "Invalid date";
	}
	if($competition->datasplit = "group"){
		if((preg_match("/[a-zA-Z]/",$gameSplit) == 0) || (strlen($gameSplit) != 1)){
			$error = "Invalid group";
		} else {
			$group = $gameSplit;
		}
	} else if($competition->datasplit = "week") {
		if((preg_match("/[0-9]/",$gameSplit) == 0) || (strlen($gameSplit) > 2)){
			$error = "Invalid week";
		} else {
			$week = $gameSplit;
		}		
	}
	if(($gameTeam1 == "") || ($gameTeam2 == "")){
		$error = "Invalid team";
	}	
	else if($gameTeam1 == $gameTeam2){
		$error = "Home and away team cannot be the same";
	}
	
	if($error == ""){
		dbgame::createGame($gameDate, $week, $group, $gameTeam1, $gameTeam2, $competitionid);
	} else {
		echo json_encode(array('error' => $error));
	}
}	
?>