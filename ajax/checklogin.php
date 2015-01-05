<?php

session_start();
$user = $_SESSION['predictionuser'];

if(!isset($reqAccessLevel)){
	$reqAccessLevel = 0;
}

if(($user == null) || (intval($user->accesslevel) < intval($reqAccessLevel))){
	return "false";
}else{
	return "OK";
}

?>
