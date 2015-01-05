<?php

	include_once 'dbuser.class.php';

	$response = array('type'=>'', 'message'=>'');
	$error = "";
	
	$email       = $_POST['email'];
	$password1   = md5($_POST['password1']);
	$password2   = md5($_POST['password2']);
	$name  		 = $_POST['name'];
		
	if($email == ""){
		$error = "Email address cannot be blank";
	}
	if($password1 == ""){
		$error = "Password cannot be blank";		
	}
	if($name == ""){
		$error = "Name cannot be blank";		
	}
	if($password1 != $password2){
		$error = "Entered passwords must match";
	}
	
	$user = dbuser::checkUser($email);
		
	if($user != null) {
		$error = "A user with that email already exists";
	} 
	
	if($error == ""){
		$userid = dbuser::createUser($email, $password1, $name);
		if($userid != 0){
			session_start();
			$_SESSION['predictionuserid'] = $userid;
		} else { 
			$error = "Database update error";
		}
	}	
	
	if($error !=""){
		$response['type'] = 'error';
		$response['message'] = $error;
	} else {
		$response['type'] = 'success';
		$response['message'] = '';
	}
	
	echo json_encode($response);
	exit;
?>