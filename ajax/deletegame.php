<?php
include_once 'dbgame.class.php';

$reqAccessLevel = 5;

if ((include 'checklogin.php') != 'OK') {
	exit('invalid login');
}

$gameid = $_POST["gameid"];

if($gameid != 0){
	$success = dbgame::deleteGame($gameid);
	if(!$success){
		echo json_encode(array("error" => "Could not delete record"));		
	}	
}	
?>