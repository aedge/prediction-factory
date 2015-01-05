<?php

	include_once 'dbleague.class.php';

	session_start();
	$user = $_SESSION['predictionuser'];
	if($user != null){	
	
		$response = array('type'=>'', 'message'=>'', 'code'=>'', 'name'=>'');
		$error = "";
		
		$action       = $_POST['leagueAction'];
		$code         = $_POST['leagueText'];
			
		if($code == ""){
			$error = "This field cannot be blank";
		}
		
		if($error == ""){
			if($action == "create"){
				$leaguecode = dbleague::createLeague($user->userid,$code);				
			} else if($action == "join"){
				$error = dbleague::joinLeague($user->userid,$code);
			}
		}	
		
		if($error !=""){
			$response['type']    = 'error';
			$response['message'] = $error;
		} else {
			$response['type']    = 'success';
			$response['message'] = '';
			$response['name']    = $name;
			$response['code']    = $leaguecode;
		}
	}
	
	print json_encode($response);
	exit;
	

?>